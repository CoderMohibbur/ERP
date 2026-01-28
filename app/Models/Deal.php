<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use HasFactory, SoftDeletes;

    public const STAGE_NEW         = 'new';
    public const STAGE_CONTACTED   = 'contacted';
    public const STAGE_QUOTED      = 'quoted';
    public const STAGE_NEGOTIATING = 'negotiating';
    public const STAGE_WON         = 'won';
    public const STAGE_LOST        = 'lost';

    public const STAGES = [
        self::STAGE_NEW,
        self::STAGE_CONTACTED,
        self::STAGE_QUOTED,
        self::STAGE_NEGOTIATING,
        self::STAGE_WON,
        self::STAGE_LOST,
    ];

    /**
     * ✅ Only business fields are mass assignable.
     * Security fields like owner_id/created_by/updated_by should be set by code (controller/observer),
     * not by user input.
     */
    protected $fillable = [
        'title',
        'lead_id',
        'client_id',
        'project_id',
        'advance_invoice_id',

        'stage',
        'value_estimated',
        'currency',
        'probability',
        'expected_close_date',

        'won_at',
        'lost_at',
        'lost_reason',
    ];

    /**
     * ✅ Guard internal/security fields.
     */
    protected $guarded = [
        'id',
        'owner_id',
        'created_by',
        'updated_by',
    ];

    protected $attributes = [
        'stage' => self::STAGE_NEW,
    ];

    protected $casts = [
        'value_estimated'     => 'decimal:2',
        'probability'         => 'integer',
        'expected_close_date' => 'date',
        'won_at'              => 'datetime',
        'lost_at'             => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function advanceInvoice()
    {
        return $this->belongsTo(Invoice::class, 'advance_invoice_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'actionable');
    }

    /**
     * Deals with expected close date due on/before given date.
     */
    public function scopeExpectedCloseDue(Builder $query, ?\DateTimeInterface $date = null): Builder
    {
        $date = $date ?: now()->toDate();

        return $query->whereNotNull('expected_close_date')
            ->whereDate('expected_close_date', '<=', $date);
    }

    /**
     * Helpful scope for filtering by stage.
     */
    public function scopeStage(Builder $query, string $stage): Builder
    {
        return $query->where('stage', $stage);
    }
}
