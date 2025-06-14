<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    protected $fillable = [
        'client_id',
        'type',
        'value',
    ];

    /**
     * 🔗 Relationship: Belongs to a client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * 🔍 Scope: Filter by type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
