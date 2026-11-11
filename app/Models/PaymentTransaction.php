<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentTransaction extends Model
{
    use LogsActivity;

    protected $table = 'payment_transactions';

    protected $fillable = [
        'payable_id',
        'payable_type',
        'amount',
        'phone_number',
        'external_reference',
        'gateway_reference',
        'provider_slug',
        'status',
        'client_name',
        'receipt_url',
        'response',
    ];

    protected $visible = [
        'payable_id',
        'payable_type',
        'amount',
        'phone_number',
        'external_reference',
        'gateway_reference',
        'provider_slug',
        'status',
        'client_name',
        'receipt_url',
    ];

    public function payable()
    {
        return $this->morphTo();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
