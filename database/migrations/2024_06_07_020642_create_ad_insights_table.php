<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ad_insights', function (Blueprint $table) {
            $table->id();
            $table->string('target')->nullable();
            $table->string('account_currency')->nullable();
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->string('onsite_conversion_post_save')->nullable();
            $table->string('comment')->nullable();
            $table->string('page_engagement')->nullable();
            $table->string('post_engagement')->nullable();
            $table->string('photo_view')->nullable();
            $table->string('post')->nullable();
            $table->string('post_reaction')->nullable();
            $table->string('link_click')->nullable();
            $table->string('impressions')->nullable();
            $table->string('clicks')->nullable();
            $table->string('conversion_rate_ranking')->nullable();
            $table->string('cost_per_inline_link_click')->nullable();
            $table->string('cost_per_inline_post_engagement')->nullable();
            $table->string('cost_per_unique_action_type_link_click')->nullable();
            $table->string('cost_per_unique_action_type_page_engagement')->nullable();
            $table->string('cost_per_unique_action_type_post_engagement')->nullable();
            $table->string('cost_per_unique_click')->nullable();
            $table->string('cpc')->nullable();
            $table->string('cpm')->nullable();
            $table->string('cpp')->nullable();
            $table->string('ctr')->nullable();
            $table->string('frequency')->nullable();
            $table->string('inline_link_clicks')->nullable();
            $table->string('inline_link_click_ctr')->nullable();
            $table->string('cost_per_action_type_onsite_conversion_post_save')->nullable();
            $table->string('cost_per_action_type_link_click')->nullable();
            $table->string('cost_per_action_type_page_engagement')->nullable();
            $table->string('cost_per_action_type_post_engagement')->nullable();
            $table->string('cost_per_outbound_click')->nullable();
            $table->string('cost_per_thruplay')->nullable();
            $table->string('cost_per_unique_inline_link_click')->nullable();
            $table->string('cost_per_unique_outbound_click')->nullable();
            $table->string('date_start')->nullable();
            $table->string('date_stop')->nullable();
            $table->string('engagement_rate_ranking')->nullable();
            $table->string('full_view_impressions')->nullable();
            $table->string('quality_ranking')->nullable();
            $table->string('spend')->nullable();
            $table->string('outbound_clicks')->nullable();
            $table->string('reach')->nullable();
            $table->string('video_30_sec_watched_actions')->nullable();
            $table->string('website_ctr')->nullable();
            $table->string('video_play_actions')->nullable();
            $table->string('video_p95_watched_actions')->nullable();
            $table->string('video_p75_watched_actions')->nullable();
            $table->string('video_p50_watched_actions')->nullable();
            $table->string('video_p25_watched_actions')->nullable();
            $table->string('video_p100_watched_actions')->nullable();
            $table->string('video_avg_time_watched_actions')->nullable();
            $table->string('social_spend')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_insights');
    }
};
