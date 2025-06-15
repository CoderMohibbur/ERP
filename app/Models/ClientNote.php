<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{
    protected $fillable = [
        'client_id',
        'created_by',
        'note',
    ];

    /**
     * 🔗 Relationship: note belongs to a client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * 🔗 Relationship: note created by a user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔍 Scope: recent first
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }


}
