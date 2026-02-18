<?php

namespace App\Models;

use App\Models\Issue;
use App\Models\Kapan;
use App\Models\KapanPart;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\Shape;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diamond extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function shapes()
    {
        return $this->belongsTo(Shape::class, 'shape');
    }

    public function kapan()
    {
        return $this->belongsTo(Kapan::class, 'kapans_id');
    }

    public function kapanPart()
    {
        return $this->belongsTo(KapanPart::class, 'kapan_parts_id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'diamonds_id');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'diamonds_id');
    }

    public function sell()
    {
        return $this->hasOne(Sell::class, 'diamonds_id');
    }
}
