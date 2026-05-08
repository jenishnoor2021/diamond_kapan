<?php

namespace App\Imports;

use App\Models\Diamond;
use App\Models\Issue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Collection;

class UpdateIssuePriceImport implements ToCollection, WithCalculatedFormulas
{
  public function collection(Collection $rows)
  {
    foreach ($rows as $index => $row) {

      if ($index == 0) continue;

      $diamondName = $row[0]; // A
      $price       = $row[9]; // J
      $totalPrice  = $row[10]; // K

      $diamond = Diamond::where('diamond_name', $diamondName)->first();

      if (!$diamond) continue;

      $issue = Issue::where('diamonds_id', $diamond->id)
        ->where('designation_id', 3)
        ->first();

      if (!$issue) continue;

      $issue->update([
        'price'       => $price,
        'total_price' => $totalPrice, // ✅ अब formula नहीं, value आएगी
      ]);
    }
  }
}
