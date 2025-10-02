<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanExport implements FromCollection, WithMapping, WithStyles, WithEvents
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function collection()
    {
        return Order::with('user')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function map($order): array
    {
        static $i = 0;
        return [
            ++$i,
            $order->created_at->format('d/m/Y'),
            $order->kode_pesanan,
            $order->user->name ?? 'Anonim',
            number_format($order->total, 0, ',', '.'),
            ucfirst($order->payment_status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 🔹 Judul Toko
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'AGA IT COMPUTER - Laporan Penjualan');
                $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 🔹 Periode laporan
                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', "Periode: {$this->start} s/d {$this->end}");
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // 🔹 Header tabel manual
                $headers = ["No", "Tanggal", "Kode Pesanan", "Pelanggan", "Total", "Status"];
                $sheet->fromArray([$headers], null, 'A3');

                // 🔹 Style header
                $sheet->getStyle('A3:F3')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '2563EB'], // biru
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // 🔹 Auto size kolom
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // 🔹 Border semua tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A3:F{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // 🔹 Tambahkan total penjualan
                $total = \App\Models\Order::whereBetween('created_at', [$this->start, $this->end])->sum('total');
                $totalRow = $lastRow + 2;
                $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", "Total Penjualan");
                $sheet->setCellValue("F{$totalRow}", "Rp " . number_format($total, 0, ',', '.'));

                $sheet->getStyle("A{$totalRow}:F{$totalRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$totalRow}:F{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
