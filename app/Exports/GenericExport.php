<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    protected $data;
    protected $title;
    protected $headings;

    public function __construct(array $data, string $title = 'Export')
    {
        $this->data = $data;
        $this->title = $title;
        $this->headings = isset($data[0]) ? $data[0] : [];
    }

    public function array(): array
    {
        // Hapus header dari data
        $dataWithoutHeader = $this->data;
        array_shift($dataWithoutHeader);
        return $dataWithoutHeader;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '2C3E50']
            ]
        ]);

        // Auto size columns
        foreach (range('A', $sheet->getHighestColumn()) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}