<?php

namespace App\Http\Controllers\Sync;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BigQueryService;
use App\Models\AdAccounts;
use App\Traits\BigQueryTrait;
use App\Traits\ArraysConvertsTrait;

class GoogleController extends Controller
{

    use BigQueryTrait;
    use ArraysConvertsTrait;

    public function __construct()
    {   
        $this->setBigQueryParameters();
    }

    public function index(){

        $allLogs = [];
        $status = 201;

        try{

            // Crea ejecutaron todas las cuentas de facebook
            $accounts = $this->adAccoundts();
            $allLogs = array_merge($allLogs, $accounts['logs']);


            $allStatus = [$accounts['status']];

            if(in_array(400, $allStatus)){
                $status = 400;
            }

            return response()->json([
                'data' => [
                    'status' => $status,
                    'logs' => $allLogs
                ]
            ])->setStatusCode($status);

        }catch(err){
            return response()->json([
                'data' => [
                    'status' => 400,
                    'msg' => 'An error occurred while executing the query'
                ]
            ])->setStatusCode(400);
        }
    }

    protected function adAccoundts(){

        $nameAccound = 'ad_accounts';
        $accoundID = 'account_id';
        $tableName = 'Meta_AD_Accounts';

        $adAccountsCreate = $this->validateData($tableName, $nameAccound, $accoundID);

        return $adAccountsCreate;

    }

}
