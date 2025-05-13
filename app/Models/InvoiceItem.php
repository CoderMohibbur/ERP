<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InvoiceItem extends Model
{
    use SoftDeletes;
    protected $fillable = ['invoice_id', 'description', 'amount'];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
