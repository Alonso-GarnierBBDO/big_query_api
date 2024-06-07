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
            $table->string('date_preset')->nullable();
            $table->string('date_start')->nullable();
            $table->string('data_end')->nullable();
            $table->string('time_increment')->nullable();
            $table->string('level')->nullable();
            $table->string('account_currency')->nullable();
            $table->string('action_attribution_windows')->nullable();
            $table->string('ad_account_id')->nullable();
            $table->string('ad_account_name')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('ad_set_id')->nullable();
            $table->string('ad_set_name')->nullable();
            $table->string('ad_id')->nullable();
            $table->string('ad_name')->nullable();
            $table->string('clicks')->nullable();
            $table->string('conversion_rate_ranking')->nullable();
            $table->string('cost_per_estimated_ad_recallers')->nullable();
            $table->string('cost_per_inline_link_click')->nullable();
            $table->string('cost_per_inline_post_engagement')->nullable();
            $table->string('cost_per_unique_click')->nullable();
            $table->string('cost_per_unique_inline_link_click')->nullable();
            $table->string('cpc')->nullable();
            $table->string('cpm')->nullable();
            $table->string('cpp')->nullable();
            $table->string('ctr')->nullable();
            $table->string('estimated_ad_recall_rate')->nullable();
            $table->string('estimated_ad_recallers')->nullable();
            $table->string('frequency')->nullable();
            $table->string('impressions')->nullable();
            $table->string('inline_link_clicks')->nullable();
            $table->string('inline_link_clicks_counter')->nullable();
            $table->string('inline_post_engagement')->nullable();
            $table->string('instant_experience_clicks_to_open')->nullable();
            $table->string('instant_experience_clicks_to_start')->nullable();
            $table->string('instant_experience_outbound_clicks')->nullable();
            $table->string('objective')->nullable();
            $table->string('qualityRanking')->nullable();
            $table->string('reach')->nullable();
            $table->string('spend')->nullable();
            $table->string('unique_clicks')->nullable();
            $table->string('unique_ctr')->nullable();
            $table->string('unique_inline_link_clicks')->nullable();
            $table->string('unique_inline_link_click_counter')->nullable();
            $table->string('unique_link_clicks_counter')->nullable();
            $table->string('checkins')->nullable();
            $table->string('event_responses')->nullable();
            $table->string('link_clicks')->nullable();
            $table->string('offer_saves')->nullable();
            $table->string('outbound_clicks')->nullable();
            $table->string('page_engagements')->nullable();
            $table->string('page_likes')->nullable();
            $table->string('page_mentions')->nullable();
            $table->string('page_photo_views')->nullable();
            $table->string('post_comments')->nullable();
            $table->string('post_engagements')->nullable();
            $table->string('post_shares')->nullable();
            $table->string('post_reactions')->nullable();
            $table->string('page_tab_views')->nullable();
            $table->string('video_3_second_views')->nullable();
            $table->string('region')->nullable();
            $table->string('ad_effective_status')->nullable();
            $table->string('use_async')->nullable();
            $table->string('default_summary')->nullable();
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
