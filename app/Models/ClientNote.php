<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{
    protected $fillable = ['client_id', 'created_by', 'note'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
