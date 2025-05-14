<?php

namespace App\Models;

use App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
