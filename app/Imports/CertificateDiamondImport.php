<?php

namespace App\Imports;

use App\Models\Clarity;
use App\Models\Color;
use App\Models\Diamond;
use App\Models\Issue;
use App\Models\Purchase;
use App\Models\Shape;
use App\Models\Worker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CertificateDiamondImport implements ToCollection, WithCalculatedFormulas
{
    protected $successCount = 0;
    protected $notFoundDiamonds = [];
    protected $errorRows = [];
    protected $importedDiamonds = [];

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        // 1. Analyze Header Row
        $firstRow = $rows->first();
        $headerMap = [];
        $hasHeader = false;

        foreach ($firstRow as $colIdx => $colVal) {
            $clean = strtolower(trim((string)$colVal));
            $clean = str_replace(['#', '_', ' ', '-', '(', ')', '/'], '', $clean);
            if ($clean !== '') {
                $headerMap[$clean] = $colIdx;
            }
        }

        // Check if header contains recognized columns
        $headerCheckKeys = ['stock', 'stockno', 'diamondname', 'certificate', 'certino', 'shape', 'shapename', 'weight'];
        foreach ($headerCheckKeys as $chk) {
            if (isset($headerMap[$chk])) {
                $hasHeader = true;
                break;
            }
        }

        // Helper to find column index with keyword fallback
        $findCol = function (array $keywords, $fallbackIdx) use ($headerMap) {
            foreach ($keywords as $kw) {
                $cleanKw = strtolower(str_replace(['#', '_', ' ', '-', '(', ')', '/'], '', $kw));
                if (isset($headerMap[$cleanKw])) {
                    return $headerMap[$cleanKw];
                }
                foreach ($headerMap as $key => $idx) {
                    if ($key === $cleanKw || strpos($key, $cleanKw) !== false || strpos($cleanKw, $key) !== false) {
                        return $idx;
                    }
                }
            }
            return $fallbackIdx;
        };

        // Determine column indexes based on header or standard 15-column position
        $certiCol    = $findCol(['certificate', 'certino', 'cert'], 0);
        $shapeCol    = $findCol(['shapename', 'shape'], 1);
        $weightCol   = $findCol(['weight', 'returnweight', 'wt'], 2);
        $m1Col       = $findCol(['measurement1', 'm1', 'length'], 3);
        $m2Col       = $findCol(['measurement2', 'm2', 'width'], 4);
        $m3Col       = $findCol(['measurement3', 'm3', 'depthm'], 5);
        $tableCol    = $findCol(['table', 'tablepercent'], 6);
        $depthCol    = $findCol(['totaldepth', 'depthpercent', 'depth'], 7);
        $polCol      = $findCol(['polorpossym', 'polorpol/sym', 'pol', 'polish'], 8);
        $symCol      = $findCol(['sym', 'symmetry'], 9);
        $cutCol      = $findCol(['cutprop', 'cutgrade', 'cut'], 10);
        $clarityCol  = $findCol(['clarity'], 11);
        $colorCol    = $findCol(['colorlong', 'color'], 12);
        $stockCol    = $findCol(['stock', 'stockno', 'diamondname', 'diamond'], 13);
        $priceCol    = $findCol(['price', 'rate'], 14);

        $discountCol = $findCol(['discount', 'discountpercent'], -1);
        $availCol    = $findCol(['availability'], -1);
        $imageCol    = $findCol(['imagelink', 'image'], -1);
        $videoCol    = $findCol(['videolink', 'video'], -1);
        $labCol      = $findCol(['lab'], -1);

        // Predefined mappings
        $polishMap = [
            'EXCELLENT' => 'EX',
            'EX'        => 'EX',
            'VERY GOOD' => 'VG',
            'VG'        => 'VG',
            'GOOD'      => 'GD',
            'GD'        => 'GD',
            'FAIR'      => 'FR',
            'FR'        => 'FR',
            'POOR'      => 'PR',
            'PR'        => 'PR',
        ];

        $cutMap = [
            'EXCELLENT' => 'EX',
            'EX'        => 'EX',
            'VERY GOOD' => 'VG',
            'VG'        => 'VG',
            'IDEAL'     => 'IDEAL',
            'ID'        => 'IDEAL',
            'GOOD'      => 'GD',
            'GD'        => 'GD',
            'NONE'      => '',
        ];

        $shapeMap = [
            'SQUARE CUSHION MODIFIED BRILLIANT' => 'SQUARE CUSHION MODIFIED',
            'SQUARE CUSHION MODIFIED'           => 'SQUARE CUSHION MODIFIED',
            'SQUARE CUSHION'                    => 'SQUARE CUSHION MODIFIED',
            'CUSHION MODIFIED BRILLIANT'        => 'CUSHION MODIFIED BRILLIANT',
            'CUSHION BRILLIANT'                 => 'CUSHION BRILLIANT',
            'CUSHION MODIFIED'                  => 'CUSHION MODIFIED BRILLIANT',
            'CUSHION'                           => 'CUSHION BRILLIANT',
            'SQUARE EMERALD CUT'                => 'ASSCHER',
            'SQUARE EMERALD'                    => 'ASSCHER',
            'EMERALD CUT'                       => 'EMERALD',
            'EMERALD'                           => 'EMERALD',
            'PEAR BRILLIANT'                    => 'PEAR',
            'PEAR CUT'                          => 'PEAR',
            'PEAR'                              => 'PEAR',
            'OVAL BRILLIANT'                    => 'OVAL',
            'OVAL CUT'                          => 'OVAL',
            'OVAL'                              => 'OVAL',
            'CUT CORNERED RECTANGULAR MODIFIED BRILLIANT' => 'RADIANT',
            'CUT CORNERED RECTANGULAR MODIFIED' => 'RADIANT',
            'RECTANGULAR MODIFIED BRILLIANT'    => 'RADIANT',
            'RADIANT BRILLIANT'                 => 'RADIANT',
            'RADIANT'                           => 'RADIANT',
            'ROUND BRILLIANT'                   => 'ROUND',
            'ROUND'                             => 'ROUND',
            'MARQUISE BRILLIANT'                => 'MARQUISE',
            'MARQUISE CUT'                      => 'MARQUISE',
            'MARQUISE'                          => 'MARQUISE',
            'HEART BRILLIANT'                   => 'HEART',
            'HEART'                             => 'HEART',
            'PRINCESS CUT'                      => 'PRINCESS',
            'PRINCESS'                          => 'PRINCESS',
            'ASSCHER CUT'                       => 'ASSCHER',
            'ASSCHER'                           => 'ASSCHER',
            'ASHOKA'                            => 'ASHOKA',
            'PORTUGUESE'                        => 'PORTUGUESE',
            'MOVAL'                             => 'MOVAL',
        ];

        $dbShapes = Shape::all();
        $dbColors = Color::all();
        $dbClarities = Clarity::all();

        foreach ($rows as $rowIndex => $row) {
            // Skip the header row
            if ($rowIndex === 0 && $hasHeader) {
                continue;
            }

            // Extract Stock #
            $stockNo = isset($row[$stockCol]) ? trim((string)$row[$stockCol]) : '';
            if ($stockNo === '' || strtolower($stockNo) === 'stock #' || strtolower($stockNo) === 'stock') {
                continue;
            }

            // Match diamond by stockNo
            $diamond = Diamond::where('diamond_name', $stockNo)->first();
            if (!$diamond) {
                $diamond = Diamond::whereRaw('LOWER(TRIM(diamond_name)) = ?', [strtolower($stockNo)])->first();
            }
            if (!$diamond) {
                // Loose match ignoring hyphens, underscores and spaces
                $cleanStock = str_replace(['-', ' ', '_'], '', strtolower($stockNo));
                $diamond = Diamond::whereRaw("LOWER(REPLACE(REPLACE(REPLACE(diamond_name, '-', ''), ' ', ''), '_', '')) = ?", [$cleanStock])->first();
            }

            if (!$diamond) {
                $this->notFoundDiamonds[] = $stockNo;
                continue;
            }

            try {
                // Extract column values
                $certiNo  = isset($row[$certiCol]) ? trim((string)$row[$certiCol]) : '';
                if ($certiNo !== '') {
                    if (strtoupper(substr($certiNo, 0, 2)) !== 'LG') {
                        $certiNo = 'LG' . $certiNo;
                    } else {
                        $certiNo = 'LG' . substr($certiNo, 2);
                    }
                }
                $rawShape = isset($row[$shapeCol]) ? trim((string)$row[$shapeCol]) : '';
                $weight   = isset($row[$weightCol]) ? (float)$row[$weightCol] : 0;
                $m1       = isset($row[$m1Col]) ? trim((string)$row[$m1Col]) : '';
                $m2       = isset($row[$m2Col]) ? trim((string)$row[$m2Col]) : '';
                $m3       = isset($row[$m3Col]) ? trim((string)$row[$m3Col]) : '';
                $table    = isset($row[$tableCol]) ? trim((string)$row[$tableCol]) : '';
                $depth    = isset($row[$depthCol]) ? trim((string)$row[$depthCol]) : '';
                $pol      = isset($row[$polCol]) ? trim((string)$row[$polCol]) : '';
                $sym      = isset($row[$symCol]) ? trim((string)$row[$symCol]) : '';
                $cutProp  = isset($row[$cutCol]) ? trim((string)$row[$cutCol]) : '';
                $clarity  = isset($row[$clarityCol]) ? trim((string)$row[$clarityCol]) : '';
                $color    = isset($row[$colorCol]) ? trim((string)$row[$colorCol]) : '';
                $price    = isset($row[$priceCol]) ? (float)$row[$priceCol] : 0;

                $discount     = ($discountCol >= 0 && isset($row[$discountCol])) ? (float)$row[$discountCol] : 0;
                $availability = ($availCol >= 0 && isset($row[$availCol]) && trim((string)$row[$availCol]) !== '') ? trim((string)$row[$availCol]) : 'GA';
                $imageLink    = ($imageCol >= 0 && isset($row[$imageCol])) ? trim((string)$row[$imageCol]) : null;
                $videoLink    = ($videoCol >= 0 && isset($row[$videoCol])) ? trim((string)$row[$videoCol]) : null;
                $lab          = ($labCol >= 0 && isset($row[$labCol]) && trim((string)$row[$labCol]) !== '') ? trim((string)$row[$labCol]) : 'IGI';

                // Shape mapping
                $shapeUpper = strtoupper(preg_replace('/\s+/', ' ', $rawShape));
                $rShape = $shapeMap[$shapeUpper] ?? null;
                if (!$rShape) {
                    foreach ($shapeMap as $k => $v) {
                        if (stripos($shapeUpper, $k) !== false) {
                            $rShape = $v;
                            break;
                        }
                    }
                }
                if (!$rShape) {
                    foreach ($dbShapes as $dbShape) {
                        if (strcasecmp($dbShape->shape_type, $rawShape) === 0 || stripos($shapeUpper, strtoupper($dbShape->shape_type)) !== false) {
                            $rShape = $dbShape->shape_type;
                            break;
                        }
                    }
                }
                $rShape = $rShape ?: ($rawShape ?: 'ROUND');

                // Polish & Symmetry mapping
                $polUpper = strtoupper($pol);
                $rPolish = $polishMap[$polUpper] ?? ($pol ?: 'EX');

                $symUpper = strtoupper($sym);
                $rSymmetry = $polishMap[$symUpper] ?? ($sym ?: 'EX');

                // Cut Grade mapping
                $cutUpper = strtoupper($cutProp);
                $cutGrade = $cutMap[$cutUpper] ?? ($cutProp ?: '');

                // Clarity mapping (normalize spaces e.g. "VS 1" -> "VS1")
                $clarityClean = str_replace([' ', '-', '_'], '', strtoupper($clarity));
                $matchedClarity = $dbClarities->first(function ($c) use ($clarityClean) {
                    return strcasecmp(str_replace(' ', '', $c->name), $clarityClean) === 0;
                });
                $rClarity = $matchedClarity ? $matchedClarity->name : ($clarityClean ?: 'VS1');

                // Color mapping
                $colorUpper = strtoupper($color);
                $matchedColor = $dbColors->first(function ($c) use ($colorUpper) {
                    return strcasecmp($c->c_name, $colorUpper) === 0;
                });
                $rColor = $matchedColor ? $matchedColor->c_name : ($colorUpper ?: 'E');

                // Measurements format (format as m1*m2*m3)
                if ($m1 !== '' && $m2 !== '' && $m3 !== '') {
                    $measurements = "{$m1}*{$m2}*{$m3}";
                } elseif ($m1 !== '') {
                    $measurements = $m1;
                } else {
                    $measurements = null;
                }

                // Certificate URL: default IGI viewer
                $certiUrl = $certiNo !== '' ? "https://api.igi.org/viewpdf.php?r={$certiNo}" : null;

                // Total price calculation: weight * price - discount%
                $baseAmount = $weight * $price;
                if ($discount > 0) {
                    $totalPrice = round($baseAmount - ($baseAmount * $discount / 100), 2);
                } else {
                    $totalPrice = round($baseAmount, 2);
                }

                // Find or create Issue for Certificate (designation_id = 3)
                $issue = Issue::where('diamonds_id', $diamond->id)
                    ->where('designation_id', 3)
                    ->where('is_return', 0)
                    ->latest('id')
                    ->first();

                if (!$issue) {
                    $issue = Issue::where('diamonds_id', $diamond->id)
                        ->where('designation_id', 3)
                        ->latest('id')
                        ->first();
                }

                if (!$issue) {
                    $worker = Worker::where('designation', 3)->where('is_active', 1)->first();
                    $issue = Issue::create([
                        'kapans_id'      => $diamond->kapans_id ?? 0,
                        'kapan_parts_id' => $diamond->kapan_parts_id ?? 0,
                        'diamonds_id'    => $diamond->id,
                        'designation_id' => 3,
                        'worker_id'      => $worker ? $worker->id : 0,
                        'issue_weight'   => $diamond->weight > 0 ? $diamond->weight : ($diamond->prediction_weight > 0 ? $diamond->prediction_weight : $weight),
                        'issue_date'     => now()->format('Y-m-d'),
                        'is_return'      => 0,
                    ]);
                }

                // Update Issue with all certified return data and standard defaults
                $issue->update([
                    'is_return'              => 1,
                    'is_non_certi'           => 0, // 0 = Certified Diamond
                    'certi_no'               => $certiNo,
                    'Certificate_url'        => $certiUrl,
                    'availability'           => $availability,
                    'return_weight'          => $weight,
                    'return_date'            => now()->format('Y-m-d'),
                    'r_shape'                => $rShape,
                    'r_color'                => $rColor,
                    'r_clarity'              => $rClarity,
                    'r_polish'               => $rPolish,
                    'r_symmetry'             => $rSymmetry,
                    'cut_grade'              => $cutGrade,
                    'price'                  => $price,
                    'discount'               => $discount,
                    'total_price'            => $totalPrice,
                    'image_link'             => $imageLink,
                    'video_link'             => $videoLink,
                    'depth_percent'          => $depth,
                    'table_percent'          => $table,
                    'fluorescence_intensity' => 'NONE',
                    'lab'                    => $lab,
                    'measurements'           => $measurements,
                    'bgm'                    => 'NONE',
                    'fancy_color'            => null,
                    'fancy_color_intensity'  => null,
                    'h_and_a'                => 'No',
                    'city'                   => 'SURAT',
                    'state'                  => 'GUJARAT',
                    'country'                => 'India',
                    'eye_clean'              => 'Yes',
                    'growth_type'            => 'CVD',
                ]);

                // Update Diamond
                $matchedShapeObj = $dbShapes->firstWhere('shape_type', $rShape);
                $diamondUpdate = ['status' => 'purchased'];
                if ($weight > 0) {
                    $diamondUpdate['weight'] = $weight;
                }
                if ($matchedShapeObj) {
                    $diamondUpdate['shape'] = $matchedShapeObj->id;
                }
                $diamond->update($diamondUpdate);

                // Ensure purchase record exists
                Purchase::firstOrCreate([
                    'diamonds_id' => $diamond->id,
                ]);

                $this->successCount++;
                $this->importedDiamonds[] = $stockNo;
            } catch (\Exception $e) {
                Log::error("Import error for stock {$stockNo}: " . $e->getMessage());
                $this->errorRows[] = "{$stockNo} (" . $e->getMessage() . ")";
            }
        }
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getNotFoundDiamonds(): array
    {
        return $this->notFoundDiamonds;
    }

    public function getErrorRows(): array
    {
        return $this->errorRows;
    }

    public function getImportedDiamonds(): array
    {
        return $this->importedDiamonds;
    }
}

