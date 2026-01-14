<?php

namespace App\Models;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function designations()
    {
        return $this->belongsTo(Designation::class, 'designation', 'id');
    }
}
