<?php

namespace App\Models;

use App\Models\Kapan;
use App\Models\Worker;
use App\Models\Diamond;
use App\Models\KapanPart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Issue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kapans()
    {
        return $this->belongsTo(Kapan::class, 'kapans_id');
    }

    public function kapanPart()
    {
        return $this->belongsTo(KapanPart::class, 'kapan_parts_id', 'id');
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function diamond()
    {
        return $this->belongsTo(Diamond::class, 'diamonds_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    // Status Accessor
    public function getStatusAttribute()
    {
        if ($this->return_date && $this->return_weight > 0) {
            return 'Returned';
        }

        return 'Pending';
    }
}
