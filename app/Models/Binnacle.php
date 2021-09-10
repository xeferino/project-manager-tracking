<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Binnacle extends Model
{
    use HasFactory;

    protected $fillable = [
        'observation',
        'project_id',
        'user_id',
        'department_send_id',
        'department_received_id',
        'status',
        'annexes'
    ];

    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

    static function Department($id)
    {
        return Department::find($id)->name ?? '--------';
    }
}
