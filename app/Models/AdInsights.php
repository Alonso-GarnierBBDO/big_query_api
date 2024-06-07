<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdInsights extends Model
{
    use HasFactory;

    protected $fillable = [
        'target',
        'account_currency',
        'account_id',
        'account_name',
        'onsite_conversion_post_save',
        'comment',
        'page_engagement',
        'post_engagement',
        'photo_view',
        'post',
        'post_reaction',
        'link_click',
        'impressions',
        'clicks',
        'conversion_rate_ranking',
        'cost_per_inline_link_click',
        'cost_per_inline_post_engagement',
        'cost_per_unique_action_type_link_click',
        'cost_per_unique_action_type_page_engagement',
        'cost_per_unique_action_type_post_engagement',
        'cost_per_unique_click',
        'cpc',
        'cpm',
        'cpp',
        'ctr',
        'frequency',
        'inline_link_clicks',
        'inline_link_click_ctr',
        'cost_per_action_type_onsite_conversion_post_save',
        'cost_per_action_type_link_click',
        'cost_per_action_type_page_engagement',
        'cost_per_action_type_post_engagement',
        'cost_per_outbound_click',
        'cost_per_thruplay',
        'cost_per_unique_inline_link_click',
        'cost_per_unique_outbound_click',
        'date_start',
        'date_stop',
        'engagement_rate_ranking',
        'full_view_impressions',
        'quality_ranking',
        'spend',
        'outbound_clicks',
        'reach',
        'video_30_sec_watched_actions',
        'website_ctr',
        'video_play_actions',
        'video_p95_watched_actions',
        'video_p75_watched_actions',
        'video_p50_watched_actions',
        'video_p25_watched_actions',
        'video_p100_watched_actions',
        'video_avg_time_watched_actions',
        'social_spend'
    ];

}
