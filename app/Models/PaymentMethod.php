<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'type'];
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
