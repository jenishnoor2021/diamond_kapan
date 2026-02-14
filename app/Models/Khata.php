<?php

namespace App\Models;

use App\Models\Income;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Khata extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function incomes()
    {
        return $this->hasMany(Income::class, 'khatas_id');
    }

    public function khatabills()
    {
        return $this->hasMany(KhataBill::class, 'khatas_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'khatas_id');
    }
}
