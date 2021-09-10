<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'process_id',
        'status'
    ];

    public function Binnacles()
    {
        return $this->hasMany('App\Models\Binnacle');
    }

    public function Process()
    {
        return $this->belongsTo('App\Models\Process');
    }

    public function Annexes()
    {
        return $this->hasMany('App\Models\AnnexedProject');
    }
}
