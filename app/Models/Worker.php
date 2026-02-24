<?php

namespace App\Models;

use App\Models\Designation;
use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function designations()
    {
        return $this->belongsTo(Designation::class, 'designation', 'id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'worker_id');
    }

    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }
}
