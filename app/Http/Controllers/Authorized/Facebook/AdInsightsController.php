<?php

namespace App\Http\Controllers\Authorized\Facebook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AdInsights;
use App\Models\AdAccounts;

class AdInsightsController extends Controller
{
    public function index(){

        set_time_limit(120);

        $api = env('API_URL_FACEBOOK');
        $token = env('API_FACEBOOK_TOKEN');

        $validate = true;

        if (!$api || !$token) {
            return response()->json([
                'error' => [
                    'code' => 403,
                    'msg' => 'Facebook URL invalid or token invalid'
                ]
            ])->setStatusCode(403);
        }

        $adAccounts = AdAccounts::all();

        foreach ($adAccounts as $row) {
            
            $id = $row->account_id;

            $apiUrl = $api . "/$id/insights";
            $params = http_build_query(array(
                'access_token' => $token,
                'fields' => 'account_currency,account_id,account_name,action_values,actions,ad_id,campaign_id,campaign_name,ad_name,adset_id,adset_name,impressions,buying_type,clicks,conversion_rate_ranking,cost_per_estimated_ad_recallers,cost_per_inline_link_click,cost_per_inline_post_engagement,cost_per_unique_action_type,cost_per_unique_click,cpc,cpm,cpp,ctr,estimated_ad_recall_rate,estimated_ad_recallers,frequency,inline_link_clicks,inline_link_click_ctr,attribution_setting,canvas_avg_view_percent,canvas_avg_view_time,catalog_segment_value,conversion_values,conversions,converted_product_quantity,converted_product_value,cost_per_action_type,cost_per_conversion,cost_per_outbound_click,cost_per_thruplay,cost_per_unique_inline_link_click,cost_per_unique_outbound_click,date_start,date_stop,dda_results,engagement_rate_ranking,full_view_impressions,quality_ranking,spend,outbound_clicks,reach,video_30_sec_watched_actions,website_purchase_roas,website_ctr,video_play_curve_actions,video_play_actions,video_p95_watched_actions,video_p75_watched_actions,video_p50_watched_actions,video_p25_watched_actions,video_p100_watched_actions,video_avg_time_watched_actions,social_spend',
                'limit' => 1000,
                'date_preset' => 'this_year',
                'time_increment' => '1'
            ));

            $response = Http::timeout(120)->get($apiUrl . '?' . $params);

            if ($response->successful()) {
                $response_facebook = $response->json();

                $allFacebookInsights = $response_facebook['data'];

                foreach ($allFacebookInsights as $data){
                    /**
                     * Get all actions
                     */


                    if (isset($data['actions'])) {
                        $allActions = $data['actions'];
                    
                        foreach ($allActions as $action) {
                            $action_type = $action['action_type'];
                            $value = $action['value'];
                            $variable_name = str_replace('.', '_', $action_type);
                            ${$variable_name} = $value;
                        }
                    }

                    $link_click = $link_click ?? null;
                    $onsite_conversion_post_save = $onsite_conversion_post_save ?? null;
                    $comment = $comment ?? null;
                    $page_engagement = $page_engagement ?? null;
                    $post_engagement = $post_engagement ?? null;
                    $photo_view = $photo_view ?? null;
                    $post = $post ?? null;
                    $post_reaction = $post_reaction ?? null;

                    /**
                     * Unique action
                     */


                    if (isset($data['cost_per_unique_action_type'])) {
                        $cost_per_unique_action_type = $data['cost_per_unique_action_type'];

                        foreach ($cost_per_unique_action_type as $item) {
                            $action_type = $item['action_type'];
                            $value = $item['value'];
                            $variable_name = str_replace('.', '_', $action_type);
                            ${$variable_name} = $value;
                        }
                    }

                    $cost_per_unique_action_type_link_click = $link_click ?? null;
                    $cost_per_unique_action_type_page_engagement = $page_engagement ?? null;
                    $cost_per_unique_action_type_post_engagement = $post_engagement ?? null;

                    /**
                     * Cost per action type
                     */

                    if (isset($data['cost_per_action_type'])) {
                        $cost_per_action_type = $data['cost_per_action_type'];

                        foreach ($cost_per_action_type as $item) {
                            $action_type = $item['action_type'];
                            $value = $item['value'];
                            $variable_name = str_replace('.', '_', $action_type);
                            ${$variable_name} = $value;
                        }
                    }

                    $cost_per_action_type_onsite_conversion_post_save = $onsite_conversion_post_save ?? null;
                    $cost_per_action_type_link_click = $link_click ?? null;
                    $cost_per_action_type_page_engagement = $page_engagement ?? null;
                    $cost_per_action_type_post_engagement = $post_engagement ?? null;

                    /**
                     * Others datas
                     */

                    $date_start = $data['date_start'] ?? null;
                    $account_currency = $data['account_currency'] ?? null;
                    $account_id = $data['account_id'] ?? null;
                    $account_name = $data['account_name'] ?? null;
                    $impressions = $data['impressions'] ?? null;
                    $clicks = $data['clicks'] ?? null;
                    $conversion_rate_ranking = $data['conversion_rate_ranking'] ?? null;
                    $cost_per_inline_link_click = $data['cost_per_inline_link_click'] ?? null;
                    $cost_per_inline_post_engagement = $data['cost_per_inline_post_engagement'] ?? null;
                    $cost_per_unique_click = $data['cost_per_unique_click'] ?? null;
                    $cpc = $data['cpc'] ?? null;
                    $cpm = $data['cpm'] ?? null;
                    $cpp = $data['cpp'] ?? null;
                    $ctr = $data['ctr'] ?? null;
                    $frequency = $data['frequency'] ?? null;
                    $inline_link_clicks = $data['inline_link_clicks'] ?? null;
                    $inline_link_click_ctr = $data['inline_link_click_ctr'] ?? null;
                    $cost_per_outbound_click = isset($data['cost_per_outbound_click']) ? json_encode($data['cost_per_outbound_click']) : null;
                    $cost_per_thruplay = isset($data['cost_per_thruplay']) ? json_encode($data['cost_per_thruplay']) : null;
                    $cost_per_unique_inline_link_click = $data['cost_per_unique_inline_link_click'] ?? null;
                    $cost_per_unique_outbound_click = isset($data['cost_per_unique_outbound_click']) ? json_encode($data['cost_per_unique_outbound_click']) : null;
                    $date_stop = $data['date_stop'] ?? null;
                    $engagement_rate_ranking = $data['engagement_rate_ranking'] ?? null;
                    $full_view_impressions = $data['full_view_impressions'] ?? null;
                    $quality_ranking = $data['quality_ranking'] ?? null;
                    $spend = $data['spend'] ?? null;
                    $outbound_clicks = isset($data['outbound_clicks']) ? json_encode($data['outbound_clicks']) : null;
                    $reach = $data['reach'] ?? null;
                    $video_30_sec_watched_actions = isset($data['video_30_sec_watched_actions']) ? json_encode($data['video_30_sec_watched_actions']) : null;
                    $website_ctr = isset($data['website_ctr']) ? json_encode($data['website_ctr']) : null;
                    $video_play_actions = isset($data['video_play_actions']) ? json_encode($data['video_play_actions']) : null;
                    $video_p95_watched_actions = isset($data['video_p95_watched_actions']) ? json_encode($data['video_p95_watched_actions']) : null;
                    $video_p75_watched_actions = isset($data['video_p75_watched_actions']) ? json_encode($data['video_p75_watched_actions']) : null;
                    $video_p50_watched_actions = isset($data['video_p50_watched_actions']) ? json_encode($data['video_p50_watched_actions']) : null;
                    $video_p25_watched_actions = isset($data['video_p25_watched_actions']) ? json_encode($data['video_p25_watched_actions']) : null;
                    $video_p100_watched_actions = isset($data['video_p100_watched_actions']) ? json_encode($data['video_p100_watched_actions']) : null;
                    $video_avg_time_watched_actions = isset($data['video_avg_time_watched_actions']) ? json_encode($data['video_avg_time_watched_actions']) : null;
                    $social_spend = $data['social_spend'] ?? null;
                    


                    AdInsights::updateOrCreate(
                        [
                            'account_id' => $id,
                            'date_start' => $date_start
                        ],
                        [
                            'target' => $account_id,
                            'account_currency' => $account_currency,
                            'account_name' => $account_name,
                            'onsite_conversion_post_save' => $onsite_conversion_post_save,
                            'comment' => $comment,
                            'page_engagement' => $page_engagement,
                            'post_engagement' => $post_engagement,
                            'photo_view' => $photo_view,
                            'post' => $post,
                            'post_reaction' => $post_reaction,
                            'link_click' => $link_click,
                            'impressions' => $impressions,
                            'clicks' => $clicks,
                            'conversion_rate_ranking' => $conversion_rate_ranking,
                            'cost_per_inline_link_click' => $cost_per_inline_link_click,
                            'cost_per_inline_post_engagement' => $cost_per_inline_post_engagement,
                            'cost_per_unique_action_type_link_click' => $cost_per_unique_action_type_link_click,
                            'cost_per_unique_action_type_page_engagement' => $cost_per_unique_action_type_page_engagement,
                            'cost_per_unique_action_type_post_engagement' => $cost_per_unique_action_type_post_engagement,
                            'cost_per_unique_click' => $cost_per_unique_click,
                            'cpc' => $cpc,
                            'cpm' => $cpm,
                            'cpp' => $cpp,
                            'ctr' => $ctr,
                            'frequency' => $frequency,
                            'inline_link_clicks' => $inline_link_clicks,
                            'inline_link_click_ctr' => $inline_link_click_ctr,
                            'cost_per_action_type_onsite_conversion_post_save' => $cost_per_action_type_onsite_conversion_post_save,
                            'cost_per_action_type_link_click' => $cost_per_action_type_link_click,
                            'cost_per_action_type_page_engagement' => $cost_per_action_type_page_engagement,
                            'cost_per_action_type_post_engagement' => $cost_per_action_type_post_engagement,
                            'cost_per_outbound_click' => $cost_per_outbound_click,
                            'cost_per_thruplay' => $cost_per_thruplay,
                            'cost_per_unique_inline_link_click' => $cost_per_unique_inline_link_click,
                            'cost_per_unique_outbound_click' => $cost_per_unique_outbound_click,
                            'date_stop' => $date_stop,
                            'engagement_rate_ranking' => $engagement_rate_ranking,
                            'full_view_impressions' => $full_view_impressions,
                            'quality_ranking' => $quality_ranking,
                            'spend' => $spend,
                            'outbound_clicks' => $outbound_clicks,
                            'reach' => $reach,
                            'video_30_sec_watched_actions' => $video_30_sec_watched_actions,
                            'website_ctr' => $website_ctr,
                            'video_play_actions' => $video_play_actions,
                            'video_p95_watched_actions' => $video_p95_watched_actions,
                            'video_p75_watched_actions' => $video_p75_watched_actions,
                            'video_p50_watched_actions' => $video_p50_watched_actions,
                            'video_p25_watched_actions' => $video_p25_watched_actions,
                            'video_p100_watched_actions' => $video_p100_watched_actions,
                            'video_avg_time_watched_actions' => $video_avg_time_watched_actions,
                            'social_spend' => $social_spend,
                        ]
                    );
                }
            }
        }


        return response()->json([
            'data' => [
                'code' => 202,
                'msg' => 'Data synchronized successfully'
            ]
        ])->setStatusCode(202);

    }
}
