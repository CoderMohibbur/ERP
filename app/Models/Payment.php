<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Payment extends Model
{
    use SoftDeletes;
    protected $fillable = ['invoice_id', 'payment_method_id', 'amount', 'paid_at'];
    protected $dates = ['paid_at'];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
