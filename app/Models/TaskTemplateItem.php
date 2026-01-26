<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskTemplateItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'task_template_id',
        'title',
        'description',
        'default_status',
        'sort_order',
        'estimate_minutes',
        'role_hint',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(TaskTemplate::class, 'task_template_id');
    }
}
