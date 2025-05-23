<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProjectFile extends Model
{
    use SoftDeletes;
    protected $fillable = ['project_id', 'file_path', 'file_type'];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
