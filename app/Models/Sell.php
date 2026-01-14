<?php

namespace App\Models;

use App\Models\Party;
use App\Models\Diamond;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sell extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function diamond()
    {
        return $this->belongsTo(Diamond::class, 'diamonds_id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'parties_id');
    }
}
