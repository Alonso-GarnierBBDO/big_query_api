<?php

namespace App\Traits;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

trait ColumnsNamesTrait {

    protected $skipElements = ['id', 'user_id', 'created_at', 'updated_at', 'slug'];

    public function getColumnsDataBase($name_table){

        $getColumns = Schema::getColumnListing($name_table);
        $resetColumns = [];


        foreach ($getColumns as $value) {
            if(!in_array($value, $this->skipElements)){
                array_push($resetColumns, [
                    'name' => $value,
                    'type' => 'string',
                ]);
            }
        }

        return $resetColumns;
    }

    public function getAllItemsSave($name_table){

        $save = [];

        try{
            $query = DB::table($name_table)->get();

            foreach($query as $value){
                foreach($this->skipElements as $item){
                    unset($value->$item);
                }
                array_push($save, $value);
            }

        }catch(\Exception $e){
            array_push($save, 'Error getting the columns');
        }

        if(count($save)){
            $save = json_decode(json_encode($save), true);
        }

        return $save;
    }

}