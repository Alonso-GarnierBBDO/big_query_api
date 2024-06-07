<?php

namespace App\Services;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Schema;

class BigQueryService
{
    protected $bigQuery;

    public function __construct()
    {
        $this->bigQuery = new BigQueryClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
            'keyFilePath' => storage_path('app/google-cloud/big-query-421322-c544a8429072.json'),
        ]);
    }

    public function runQuery($query)
    {
        $job = $this->bigQuery->query($query)->run();

        $results = [];
        foreach ($job->rows() as $row) {
            $results[] = $row;
        }

        return $results;
    }

    public function tableExists($datasetId, $tableId)
    {
        $dataset = $this->bigQuery->dataset($datasetId);
        $table = $dataset->table($tableId);

        return $table->exists();
    }

    public function createTable($datasetId, $tableId, $schemaDefinition)
    {

        try{
            $dataset = $this->bigQuery->dataset($datasetId);
            if($dataset){
                $table = $dataset->table($tableId);

                if (!$table->exists()) {
                    $tableOptions = [
                        'schema' => [
                            'fields' => $schemaDefinition
                        ]
                    ];
                    $dataset->createTable($tableId, $tableOptions);
                }

                return [
                    "status" => 201,
                    "msgs" => [
                        "The table '$tableId' has been created successfully"
                    ]
                ];
            }

        }catch(\Exception $e){
            return [
                "status" => 400,
                "msgs" => [
                    "Error creating the record. Verify that the '$datasetId' data set exists."
                ]
            ];
        }
    }
    
    public function upsertData($datasetId, $tableId, $data, $uniqueKey)
    {   
        
        try{
            $searchItems = $this->bigQuery->query(
                "SELECT * FROM `" . env('GOOGLE_CLOUD_PROJECT_ID') . ".$datasetId.$tableId" . "`"
            );
    
            $updateData = [];
            $createData = [];
            $bigQueryData = [];
    
            $queryResults = $this->bigQuery->runQuery($searchItems);
    
            foreach($queryResults as $rows){
                array_push($bigQueryData, $rows);
            }
    
            foreach($data as $rows){
    
                $searchItem = array_filter($bigQueryData, fn($item) => $item[$uniqueKey] == $rows[$uniqueKey]);
    
                if(count($searchItem)){
                    $item = reset($searchItem);
                    if($item != $rows){
                        array_push($updateData, $rows);
                    }
                }else{
                    array_push($createData, $rows);
                }
            }
    
            /** Remove duplicate items */
            $updateData = array_unique($updateData, SORT_REGULAR);
    
            if(count($updateData)){
                foreach($updateData as $row){  
                    $this->update($datasetId, $tableId, $uniqueKey, $row[$uniqueKey], $row);
                }
            }
    
            if(count($createData)){
                $this->insert($datasetId, $tableId, $createData);
            }

            return true;

        }catch(\Exception $e){
            return false;
        }
        
    }

    public function update($datasetId, $tableId, $uniqueKey, $key, $newData){

        $dataset = $this->bigQuery->dataset($datasetId);
        $table = $dataset->table($tableId);

        // Construir la consulta de actualización
        $query = sprintf(
            'UPDATE `%s.%s` SET %s WHERE %s = @%s',
            $datasetId,
            $tableId,
            implode(', ', array_map(function ($field) {
                return sprintf('%s = @%s', $field, $field);
            }, array_keys($newData))),
            $uniqueKey,
            $uniqueKey
        );

        $parameters = [
            $uniqueKey => $key,
        ];
    
        foreach ($newData as $field => $value) {
            $parameters[$field] = $value;
        }

        $queryJobConfig = $this->bigQuery->query($query)->parameters($parameters);

        $queryResults = $this->bigQuery->runQuery($queryJobConfig);

        // Verificar los resultados
        if ($queryResults->isComplete()) {
            return true;
        }else{
            return false;
        }
    }

    public function insert($datasetId, $tableId, $newData){
        $dataset = $this->bigQuery->dataset($datasetId);
        $table = $dataset->table($tableId);

        $insertRows = [];
        foreach ($newData as $row) {
            $insertRows[] = ['data' => $row];
        }

        $insertResponse = $table->insertRows($insertRows);

        if ($insertResponse->isSuccessful()) {
            return true;
        }else{
            return false;
        }
    }

}