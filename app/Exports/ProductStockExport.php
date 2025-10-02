<?php

namespace App\Exports;

use App\Models\Product_Stok;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductStockExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Product_Stok::with('product.category')->get()->map(function ($stock) {
            return [
                'Nama Produk'  => $stock->product->nama_produk,
                'Tipe'         => ucfirst($stock->tipe),
                'Jumlah'       => $stock->jumlah,
                'Keterangan'   => $stock->keterangan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Tipe',
            'Jumlah',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->applyFromArray([

            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'white'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '1F4E79'
                ]
            ]
        ]);

        // Border untuk seluruh data (otomatis)
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:E$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);
    }
}
