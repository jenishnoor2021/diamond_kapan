<?php

namespace App\Models;

use App\Models\Kapan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KapanPart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kapans()
    {
        return $this->belongsTo(Kapan::class, 'kapans_id');
    }
}
