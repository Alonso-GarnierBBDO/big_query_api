<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAccounts extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'account_id',
        'account_status',
        'amount_spent',
        'age',
        'balance',
        'business_city',
        'business_country_code',
        'business_name',
        'business_street',
        'business_street2',
        'capabilities',
        'created_time',
        'currency',
        'min_campaign_group_spend_cap',
        'offsite_pixels_tos_accepted',
        'spend_cap',
        'timezone_id',
        'timezone_name',
        'slug'
    ];
}
