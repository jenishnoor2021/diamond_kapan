<?php

namespace App\Models;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Designation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
