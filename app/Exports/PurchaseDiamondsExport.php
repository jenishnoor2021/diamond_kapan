<?php

namespace App\Exports;

use App\Models\Diamond;
use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PurchaseDiamondsExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnFormatting,
    WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Purchase::where('is_sell', 0)
            ->whereHas('diamond.issues', function ($q) {
                $q->where('designation_id', 3);
            })
            ->with([
                'diamond',
                'diamond.issues' => function ($q) {
                    $q->where('designation_id', 3);
                }
            ])
            ->get()
            ->map(function ($purchase) {

                $diamond = $purchase->diamond;
                $issue   = $diamond->issues->first(); // designation 3 issue

                return [
                    'Stock #' => $diamond->diamond_name ?? '',
                    'availability' => $issue->availability ?? '',
                    'shape' => $issue->r_shape ?? '',
                    'weight' => $issue->return_weight ?? 0,
                    'clarity' => $issue->r_clarity ?? '',
                    'color' => $issue->r_color ?? '',
                    'fancy_color' => '',
                    'fancy_color_intensity' => '',
                    'fancy_color_overtone' => '',
                    'price' => $issue->price ?? 0,
                    'total_price' => $issue->total_price ?? 0,
                    'discount_percent' => '',
                    'image_link' => $issue->image_link ?? '',
                    'video_link' => $issue->video_link ?? '',
                    'cut_grade' => '',
                    'polish' => $issue->r_polish ?? '',
                    'symmetry' => $issue->r_symmetry ?? '',
                    'depth_percent' => $issue->depth_percent ?? 0,
                    'table_percent' => $issue->table_percent ?? 0,
                    'fluorescence_intensity' => $issue->fluorescence_intensity ?? '',
                    'fluorescence_color' => '',
                    'lab' => $issue->lab ?? '',
                    'certificate' => $issue->certi_no ?? '',
                    'certificate_url' => $issue->Certificate_url ?? '',
                    'cert_comment' => '',
                    'culet_condition' => '',
                    'culet_size' => '',
                    'girdle_percent' => '',
                    'girdle_condition' => '',
                    'girdle_thick' => '',
                    'girdle_thin' => '',
                    'measurements' => $issue->measurements ?? '',
                    'milky' => '',
                    'pavilion_depth' => '',
                    'bgm' => $issue->bgm ?? '',
                    'crown_height' => '',
                    'crown_angle' => '',
                    'pavilion_angle' => '',
                    'laser_inscription' => '',
                    'member_comments' => '',
                    'pair' => '',
                    'h_and_a' => $issue->h_and_a ?? '',
                    'city' => $issue->city ?? 'SURAT',
                    'state' => $issue->state ?? 'GUJARAT',
                    'country' => $issue->country ?? 'INDIA',
                    'stock_number_for_matching_pair' => '',
                    'share_access' => '',
                    'eye_clean' => $issue->eye_clean ?? 'Yes',
                    'featured' => '',
                    'table_open' => '',
                    'crown_open' => '',
                    'girdle_open' => '',
                    'star_length' => '',
                    'type' => '',
                    'tinge' => '',
                    'luster' => '',
                    'black_inclusion' => '',
                    'location_of_black' => '',
                    'table_inclusion' => '',
                    'key_to_symbol' => '',
                    'surface_graining' => '',
                    'internal_graining' => '',
                    'inclusion_pattern' => '',
                    'diamond_origin_report' => '',
                    'short_title' => '',
                    'arrival_date' => '',
                    'tags' => '',
                    'certificate_updated_at' => '',
                    'growth_type' => $issue->growth_type ?? 'CVD',
                    // 'Weight'       => $diamond->weight ?? 0,
                    // 'Prediction'   => $diamond->prediction_weight ?? 0,
                    // 'Shape'        => optional($diamond->shapes)->shape_type,
                    // 'Issue Weight' => $issue->issue_weight ?? 0,
                    // 'Return Weight' => $issue->return_weight ?? 0,
                    // 'Cert No'      => $issue->certi_no ?? '',
                    // 'Status'       => $purchase->is_sell ? 'Sold' : 'Available',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Stock #',
            'Availability',
            'Shape',
            'Weight',
            'Clarity',
            'Color',
            'Fancy Color',
            'Fancy Color Intensity',
            'Fancy Color Overtone',
            'Price',
            'Total Price',
            'Discount Percent',
            'Image Link',
            'Video Link',
            'Cut Grade',
            'Polish',
            'Symmetry',
            'Depth Percent',
            'Table Percent',
            'Fluorescence Intensity',
            'Fluorescence Color',
            'Lab',
            'Certificate #',
            'Certificate Url',
            'Cert Comment',
            'Culet Condition',
            'Culet Size',
            'Girdle Percent',
            'Girdle Condition',
            'Girdle Thick',
            'Girdle Thin',
            'Measurements',
            'Milky',
            'Pavilion Depth',
            'BGM',
            'Crown Height',
            'Crown Angle',
            'Pavilion Angle',
            'Laser Inscription',
            'Member Comments',
            'Pair',
            'H&A',
            'City',
            'State',
            'Country',
            'Stock Number For Matching Pair',
            'Share Access',
            'Eye Clean',
            'Featured',
            'Table Open',
            'Crown Open',
            'Girdle Open',
            'Star Length',
            'Type',
            'Tinge',
            'Luster',
            'Black Inclusion',
            'Location of Black',
            'Table Inclusion',
            'Key To Symbol',
            'Surface Graining',
            'Internal Graining',
            'Inclusion Pattern',
            'Diamond Origin Report',
            'Short Title',
            'Arrival Date',
            'Tags',
            'Certificate Updated At',
            'Growth Type',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // first row (headings)
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                // 'fill' => [
                //     'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //     'color' => ['rgb' => 'E9ECEF'],
                // ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Auto-size all columns
                // foreach (range('A', $sheet->getHighestColumn()) as $col) {
                //     $sheet->getColumnDimension($col)->setAutoSize(true);
                // }

                $autoSizeColumns = ['M', 'N', 'W', 'X', 'AF'];

                foreach ($autoSizeColumns as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Center align ALL cells
                $sheet->getStyle(
                    'A2:' . $sheet->getHighestColumn() . $sheet->getHighestRow()
                )->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Right-align numeric columns (adjust letters if order changes)
                $numericColumns = [
                    'D', // Weight
                    'J', // Price
                    'K', // Total Price
                    'R', // Depth %
                    'S', // Table %
                ];

                foreach ($numericColumns as $column) {
                    $sheet->getStyle($column . '2:' . $column . $sheet->getHighestRow())
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_00, // Weight
            'J' => NumberFormat::FORMAT_NUMBER_00, // Price
            'K' => NumberFormat::FORMAT_NUMBER_00, // Total Price
            'R' => NumberFormat::FORMAT_NUMBER_00, // Depth %
            'S' => NumberFormat::FORMAT_NUMBER_00, // Table %
        ];
    }
}
