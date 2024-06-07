<?php
namespace App\Traits;

trait ArraysConvertsTrait {
    public function resetResponse($array){

        print_r($array);
        $convert = array_reduce($array, function($carry, $item){
            return array_merge($carry, $item);
        }, []);

        return [];
        
    }
}