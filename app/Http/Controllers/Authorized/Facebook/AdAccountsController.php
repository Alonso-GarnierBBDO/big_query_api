<?php

namespace App\Http\Controllers\Authorized\Facebook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdAccounts;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = $request->user();
        $adAccounts = AdAccounts::where('user_id', $user->id)->get();


        if(count($adAccounts)){
            return response()->json([
                'data' => [
                    'code' => 200,
                    'items' => $adAccounts->map(function ($item) {
                        return [
                            'name' => $item->name,
                            'slug' => $item->slug,
                            'account_id' => $item->account_id,
                            'created_at' => $this->resetTime($item->created_at),
                            'updated_at' => $this->resetTime($item->updated_at),
                        ];
                    }),
                ]
            ])->setStatusCode(200);
        }

        return response()->noContent()->setStatusCode(204);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $api = env('API_URL_FACEBOOK');
        $token = env('API_FACEBOOK_TOKEN');
        $user = $request->user();


        if(!$api || !$token){
            return response()->json([
                'error' => [
                    'code' => 403,
                    'msg' => 'Facebook url invalid or token in valid'
                ]
            ])->setStatusCode(403);
        }

        $response = $this->getAllAdAccounts($token, $api);

        if ($response->failed()) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'msg' => $response['error']['message'] ?? 'Unknown error'
                ]
            ])->setStatusCode(403);
        }

        $data = $response->json()['data'] ?? [];

        foreach ($data as $adAccount) {

            $id = $adAccount['id'];
            $individualResponse = $this->getIndividualAdAccount($id, $token, $api);

            /**
             * Others datas
             */

            if ($individualResponse->failed()) {
                return response()->json([
                    'error' => [
                        'code' => 403,
                        'msg' => $response['error']['message'] ?? 'Unknown error'
                    ]
                ])->setStatusCode(403);
            }

            $name = $individualResponse['name'] ?? null;
            $account_status = $individualResponse['account_status'] ?? null;
            $amount_spent = $individualResponse['amount_spent'] ?? null;
            $age = $individualResponse['age'] ?? null;
            $balance = $individualResponse['balance'] ?? null;
            $business_city = $individualResponse['business_city'] ?? null;
            $business_country_code = $individualResponse['business_country_code'] ?? null;
            $business_name = $individualResponse['business_name'] ?? null;
            $business_street = $individualResponse['business_street'] ?? null;
            $business_street2 = $individualResponse['business_street2'] ?? null;
            $capabilities = json_encode($individualResponse['capabilities']) ?? null;
            $created_time = $individualResponse['created_time'] ?? null;
            $currency = $individualResponse['currency'] ?? null;
            $min_campaign_group_spend_cap = $individualResponse['min_campaign_group_spend_cap'] ?? null;
            $offsite_pixels_tos_accepted = $individualResponse['offsite_pixels_tos_accepted'] ?? null;
            $spend_cap = $individualResponse['spend_cap'] ?? null;
            $timezone_id = $individualResponse['timezone_id'] ?? null;
            $timezone_name = $individualResponse['timezone_name'] ?? null;
            $slug = $this->generateUniqueSlug($name);


            AdAccounts::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'account_id' => $id,
                ],
                [
                    'name' => $name,
                    'account_status' => $account_status,
                    'amount_spent' => $amount_spent,
                    'age' => $age,
                    'balance' => $balance,
                    'business_city' => $business_city,
                    'business_country_code' => $business_country_code,
                    'business_name' => $business_name,
                    'business_street' => $business_street,
                    'business_street2' => $business_street2,
                    'capabilities' => $capabilities,
                    'created_time' => $created_time,
                    'currency' => $currency,
                    'min_campaign_group_spend_cap' => $min_campaign_group_spend_cap,
                    'offsite_pixels_tos_accepted' => $offsite_pixels_tos_accepted,
                    'spend_cap' => $spend_cap,
                    'timezone_id' => $timezone_id,
                    'timezone_name' => $timezone_name,
                    'slug' => $slug,
                ]
            );
        }

        return response()->json([
            'data' => [
                'code' => 202,
                'msg' => 'Data synchronized successfully'
            ]
        ])->setStatusCode(202);
    }

    protected function getAllAdAccounts($token, $api){

        $api = $api . '/me/adaccounts';
        $params = http_build_query(array(
            'access_token' => $token,
            'fields' => 'name',
            'limit' => 1000000
        ));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api . '?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($ch);

        curl_close($ch);

        $response = Http::get($api . '?' . $params);

        return $response;

    }

    protected function getIndividualAdAccount($id, $token, $api){
        $api = $api . "/$id";

        $params = http_build_query(array(
            'access_token' => $token,
            'fields' => 'name,account_status,amount_spent,account_id,age,balance,business_city,business_country_code,business_name,business_state,business_street,business_street2,business_zip,capabilities, created_time,currency,min_campaign_group_spend_cap,offsite_pixels_tos_accepted,spend_cap,timezone_id,timezone_name',
        ));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api . '?' . $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $exec = curl_exec($ch);

        curl_close($ch);

        $response = Http::get($api . '?' . $params);

        return $response;
    }

    protected function resetTime($time){
        return Carbon::parse($time)->diffForHumans();
    }

    protected function generateUniqueSlug($name) {
        $slug = Str::slug($name);
        $originalSlug = $slug;
    
        // Verificar si el slug ya existe en la base de datos
        $count = 1;
        while (AdAccounts::where('slug', $slug)->exists()) {
            // Si existe, añadir un sufijo numérico
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
    
        return $slug;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $user = $request->user();
        $adAccount = AdAccounts::where('slug', $id)->where('user_id', $user->id)->first();

        if($adAccount){
            return response()->json([
                'data' => [
                    'code' => 200,
                    'item' => [
                        'name' => $adAccount->name,
                        'account_id' => $adAccount->account_id,
                        'account_status' => $adAccount->account_status,
                        'amount_spent' => $adAccount->amount_spent,
                        'age' => $adAccount->age,
                        'balance' => $adAccount->balance,
                        'business_city' => $adAccount->business_city,
                        'business_country_code' => $adAccount->business_country_code,
                        'business_name' => $adAccount->business_name,
                        'business_street' => $adAccount->business_street,
                        'business_street2' => $adAccount->business_street2,
                        'capabilities' => json_decode($adAccount->capabilities),
                        'created_time' => $this->resetTime($adAccount->created_time),
                        'currency' => $adAccount->currency,
                        'min_campaign_group_spend_cap' => $adAccount->min_campaign_group_spend_cap,
                        'offsite_pixels_tos_accepted' => $adAccount->offsite_pixels_tos_accepted,
                        'spend_cap' => $adAccount->spend_cap,
                        'timezone_id' => $adAccount->timezone_id,
                        'created_at' => $this->resetTime($adAccount->created_at),
                        'updated_at' => $this->resetTime($adAccount->updated_at)
                    ]
                ]
            ])->setStatusCode(200);
        }

        return response()->json([
            'data' => [
                'code' => 404,
                'msg' => 'Page not found'
            ]
        ])->setStatusCode(404);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = $request->user();
        $api = env('API_URL_FACEBOOK');
        $token = env('API_FACEBOOK_TOKEN');

        if(!$api || !$token){
            return response()->json([
                'error' => [
                    'code' => 403,
                    'msg' => 'Facebook url invalid or token in valid'
                ]
            ])->setStatusCode(403);
        }

        $adAccount = AdAccounts::where('slug', $id)->where('user_id', $user->id)->first();
        

        /**
         * Others datas
         */
        if($adAccount){

            $individualResponse = $this->getIndividualAdAccount($adAccount->account_id, $token, $api);

            if ($individualResponse->failed()) {
                return response()->json([
                    'error' => [
                        'code' => 403,
                        'msg' => $response['error']['message'] ?? 'Unknown error'
                    ]
                ])->setStatusCode(403);
            }

            $adAccount->name = $individualResponse['name'] ?? null;
            $adAccount->account_status = $individualResponse['account_status'] ?? null;
            $adAccount->amount_spent = $individualResponse['amount_spent'] ?? null;
            $adAccount->age = $individualResponse['age'] ?? null;
            $adAccount->balance = $individualResponse['balance'] ?? null;
            $adAccount->business_city = $individualResponse['business_city'] ?? null;
            $adAccount->business_country_code = $individualResponse['business_country_code'] ?? null;
            $adAccount->business_name = $individualResponse['business_name'] ?? null;
            $adAccount->business_street = $individualResponse['business_street'] ?? null;
            $adAccount->business_street2 = $individualResponse['business_street2'] ?? null;
            $adAccount->capabilities = json_encode($individualResponse['capabilities']) ?? null;
            $adAccount->created_time = $individualResponse['created_time'] ?? null;
            $adAccount->currency = $individualResponse['currency'] ?? null;
            $adAccount->min_campaign_group_spend_cap = $individualResponse['min_campaign_group_spend_cap'] ?? null;
            $adAccount->offsite_pixels_tos_accepted = $individualResponse['offsite_pixels_tos_accepted'] ?? null;
            $adAccount->spend_cap = $individualResponse['spend_cap'] ?? null;
            $adAccount->timezone_id = $individualResponse['timezone_id'] ?? null;
            $adAccount->timezone_name = $individualResponse['timezone_name'] ?? null;

            $adAccount->save();

            return response()->noContent()->setStatusCode(202);


        }

        return response()->json([
            'data' => [
                'code' => 404,
                'msg' => 'Page not found'
            ]
        ])->setStatusCode(404);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
