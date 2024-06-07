<?php
namespace App\Traits;

use App\Services\BigQueryService;
use App\Traits\ColumnsNamesTrait;

trait BigQueryTrait {

    use ColumnsNamesTrait;

    protected $bigQueryService;
    protected $datasetId;


    // Definimos los parametros iniciales
    public function setBigQueryParameters(){

        $this->bigQueryService = app(BigQueryService::class);
        $this->datasetId = env("DATASET_ID");
        
    }

    public function validateData($tableID, $tableName){

        $logs = [];
        $create = '';
        $inject = '';
        $status = 201;

        if(!$this->validateTable($tableID)){

            $create = $this->createNewTable($tableID, $tableName);
            $status = $create['status'];

            $logs = array_merge($logs, (array)$create['msgs']);

            if($status == 201){
                $inject = $this->injectData($tableID, $tableName);
                $status = $inject['status'];
                $logs = array_merge($logs, (array)$inject['msgs']);
            }

        }else{
            
            $inject = $this->injectData($tableID, $tableName);
            $status = $inject['status'];
            $logs = array_merge($logs, (array)$inject['msgs']);

        }

        return [
            'status' => $status,
            'logs' => $logs
        ];

    }

    /**
     * Creamos la tabla
     */
    protected function createNewTable($tableID, $tableName){

        try{
            $columns = $this->getColumnsDataBase($tableName);
            $schemaDefinition = $columns;
            $table = $this->bigQueryService->createTable($this->datasetId, $tableID, $schemaDefinition);
            $status = $table['status'];

            return [
                "status" => $status,
                "msgs" => $table['msgs'],
            ];

        }catch(\Exception $e){

            return [
                "status" => 400,
                "msgs" => [
                    "Error creating table '$tableID'"
                ]
            ];

        }

    }

    public function injectData($tableID, $tableName){

        try{

            $data = $this->getAllItemsSave($tableName);
            $response = $this->bigQueryService->upsertData($this->datasetId, $tableID, $data);
            return [
                "status" => 201,
                "msgs" => [
                    "The data was successfully injected into the '$tableID' table"
                ]
            ];

        }catch(\Exception $e){
            
            return [
                "status" => 400,
                "msgs" => [
                    "Error injecting data into the '$tableID' table"
                ]
            ];

        }

    }


    protected function validateTable($tableID){
        try {
            $exists = $this->bigQueryService->tableExists($this->datasetId, $tableID);
            return $exists;
        } catch (\Exception $e) {
            return false;
        }
    }

}