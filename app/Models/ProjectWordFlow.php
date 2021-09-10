<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectWordFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_created_id',
        'user_accepted_id',
        'step',
        'wordflow_id',
        'wordflow_department_id',
        'wordflow_department',
        'wordflow_status',
        'project_id',
    ];
}
