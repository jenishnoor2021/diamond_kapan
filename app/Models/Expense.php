<?php

namespace App\Models;

use App\Models\Khata;
use App\Models\KhataBill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function khatas()
    {
        return $this->belongsTo(Khata::class);
    }

    public function khataBills()
    {
        return $this->belongsTo(KhataBill::class);
    }
}
