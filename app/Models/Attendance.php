<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['employee_id', 'date', 'status', 'note'];
    protected $dates = ['date'];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // app/Models/Attendance.php

    protected $casts = [
        'date' => 'date',
    ];
}
