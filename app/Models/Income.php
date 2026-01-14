<?php

namespace App\Models;

use App\Models\Khata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function khatas()
    {
        return $this->belongsTo(Khata::class, 'khatas_id');
    }
}
