<?php

namespace App\Models;

use App\Models\KapanPart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kapan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kapanparts()
    {
        return $this->hasMany(KapanPart::class);
    }
}
