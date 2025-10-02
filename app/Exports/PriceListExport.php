<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PriceListExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products->map(function ($product) {
            return [
                'Nama Produk' => $product->nama_produk,
                'Kategori'    => $product->category->nama_kategori ?? '-',
                'Brand'       => $product->brand->name ?? '-',
                'Harga'       => $product->priceList->price ?? $product->harga,
                'Stok'        => $product->stok > 0 ? $product->stok . ' Tersedia' : 'Habis',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'Brand',
            'Harga',
            'Stok',
        ];
    }

    // Geser heading ke baris 2
    public function startCell(): string
    {
        return 'A2';
    }

    public function styles(Worksheet $sheet)
    {
        // Tambah judul besar di atas (baris 1, merge dari A1 sampai E1)
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'PRICE LIST AGA KOMPUTER');

        // Style judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center',
            ],
        ]);

        // Style header tabel (baris 2 → A2:E2)
        $sheet->getStyle('A2:E2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
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
                    'rgb' => '2563EB', // biru
                ],
            ],
        ]);

        // Border untuk seluruh isi data (mulai dari baris 3 ke bawah)
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();
        $sheet->getStyle("A3:{$lastCol}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }
}
