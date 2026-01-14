<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $uploads = '/companysign/';

    public function getSignAttribute($photo)
    {

        return $this->uploads . $photo;
    }
}
