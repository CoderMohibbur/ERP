<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProjectNote extends Model
{
    protected $fillable = ['project_id', 'created_by', 'note'];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
