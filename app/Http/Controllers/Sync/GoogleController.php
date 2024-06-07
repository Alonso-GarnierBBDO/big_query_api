<?php

namespace App\Http\Controllers\Sync;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BigQueryService;
use App\Models\AdAccounts;
use App\Traits\BigQueryTrait;
use App\Traits\ArraysConvertsTrait;
use Illuminate\Support\Facades\Http;
use App\Models\AdInsights;

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
        $allStatus = [];
        $status = 201;

        try{

            // Crea ejecutaron todas las cuentas de facebook

            $accounts = $this->adAccoundts();
            $allLogs = array_merge($allLogs, $accounts['logs']);
            array_push($allStatus, $accounts['status']);

            $insights = $this->adInsights();
            $allLogs = array_merge($allLogs, $insights['logs']);
            array_push($allStatus, $insights['status']);
            

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
        $tableName = 'Garnier_Meta_AdAccounts';

        $adAccountsCreate = $this->validateData($tableName, $nameAccound);

        return $adAccountsCreate;

    }

    protected function adInsights(){

        $nameInsights = 'ad_insights';
        $tableName = 'Garnier_Meta_AdInsights';

        $adInsightsCreate = $this->validateData($tableName, $nameInsights);

        return $adInsightsCreate;

    }


}
