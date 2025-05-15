<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'client_id', 'description', 'deadline', 'status', 'created_by'];
    protected $dates = ['deadline'];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'project_employee');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }
    public function notes()
    {
        return $this->hasMany(ProjectNote::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    protected $casts = [
        'deadline' => 'date',
    ];
}
