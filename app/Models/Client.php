<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'email', 'phone', 'address', 'created_by'];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function contacts()
    {
        return $this->hasMany(ClientContact::class);
    }
    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
