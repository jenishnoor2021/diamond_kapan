<?php

namespace App\Models;

use App\Models\Diamond;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diamond()
    {
        return $this->belongsTo(Diamond::class, 'diamonds_id');
    }
}
