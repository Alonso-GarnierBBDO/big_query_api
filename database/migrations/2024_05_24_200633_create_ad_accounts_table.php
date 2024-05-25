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
        Schema::create('ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable(); 
            $table->string('name');
            $table->string('account_id');
            $table->string('account_status')->nullable();
            $table->string('amount_spent')->nullable();
            $table->string('age')->nullable();
            $table->string('balance')->nullable();
            $table->string('business_city')->nullable();
            $table->string('business_country_code')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_street')->nullable();
            $table->string('business_street2')->nullable();
            $table->json('capabilities')->nullable();
            $table->string('created_time')->nullable();
            $table->string('currency')->nullable();
            $table->string('min_campaign_group_spend_cap')->nullable();
            $table->string('offsite_pixels_tos_accepted')->nullable();
            $table->string('spend_cap')->nullable();
            $table->string('timezone_id')->nullable();
            $table->string('timezone_name')->nullable();
            $table->string('slug');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_accounts');
    }
};
