<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRenewal extends Model
{
    use HasFactory, SoftDeletes;

    // RenewalStatus
    public const STATUS_PENDING = 'pending';
    public const STATUS_INVOICED = 'invoiced';
    public const STATUS_PAID = 'paid';
    public const STATUS_SKIPPED = 'skipped';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_INVOICED,
        self::STATUS_PAID,
        self::STATUS_SKIPPED,
    ];

    protected $fillable = [
        'service_id',
        'renewal_date',
        'period_start',
        'period_end',
        'amount',
        'invoice_id',
        'status',
        'reminded_at',
        'created_by',
    ];

    protected $casts = [
        'renewal_date' => 'date',
        'period_start' => 'date',
        'period_end'   => 'date',
        'amount'       => 'decimal:2',
        'reminded_at'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
