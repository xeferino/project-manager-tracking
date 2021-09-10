<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnexedProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name_original',
        'file_name',
        'file_path',
        'file_path_delete',
        'file_size',
        'file_type',
        'observation',
        'annexed_name',
        'project_id'
    ];
}
