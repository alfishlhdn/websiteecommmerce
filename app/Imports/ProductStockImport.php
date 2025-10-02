<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Product_Stok;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductStockImport implements OnEachRow, WithHeadingRow
{
    public array $errors = [];
    public int $processed = 0;
    public int $inserted  = 0;
    public int $skipped   = 0;

    public function onRow(Row $row)
    {
        $this->processed++;

        $i    = $row->getIndex();     // nomor baris Excel (header = 1)
        $data = $row->toArray();

        // Validasi header minimal
        $required = ['nama_produk','tipe','jumlah'];
        foreach ($required as $col) {
            if (!array_key_exists($col, $data)) {
                $this->err($i, "Kolom '{$col}' tidak ditemukan di header.");
                return;
            }
        }

        // Ambil dan rapihkan nilai
        $namaRaw   = $data['nama_produk'] ?? '';
        $tipeRaw   = $data['tipe'] ?? '';
        $jumlahRaw = $data['jumlah'] ?? '';
        $ket       = $data['keterangan'] ?? null;

        $nama   = trim((string)$namaRaw);
        $tipe   = strtolower(trim((string)$tipeRaw));

        // jumlah boleh string, kita cek numeric
        $isNumeric = is_numeric($jumlahRaw);
        $jumlah   = $isNumeric ? (int)$jumlahRaw : 0;

        // Validasi isi baris
        if ($nama === '') {
            $this->err($i, "Nama produk kosong.");
            return;
        }
        if (!in_array($tipe, ['masuk','keluar'], true)) {
            $this->err($i, "Tipe tidak valid. Harus 'masuk' atau 'keluar'. Ditemukan: '{$tipeRaw}'.");
            return;
        }
        if (!$isNumeric) {
            $this->err($i, "Jumlah bukan angka. Ditemukan: '{$jumlahRaw}'.");
            return;
        }
        if ($jumlah <= 0) {
            $this->err($i, "Jumlah harus > 0. Ditemukan: '{$jumlahRaw}'.");
            return;
        }

        // Cari produk
        $product = Product::where('nama_produk', $nama)->first();
        if (!$product) {
            $this->err($i, "Produk '{$nama}' tidak ditemukan di database.");
            return;
        }

        // Cek stok bila keluar
        if ($tipe === 'keluar' && $product->stok < $jumlah) {
            $this->err(
                $i,
                "Stok tidak cukup untuk '{$nama}'. Stok saat ini: {$product->stok}, diminta: {$jumlah}."
            );
            return;
        }

        // Simpan riwayat stok
        Product_Stok::create([
            'product_id' => $product->id,
            'tipe'       => $tipe,
            'jumlah'     => $jumlah,
            'keterangan' => $ket,
        ]);

        // Update stok produk
        if ($tipe === 'masuk') {
            $product->stok += $jumlah;
        } else {
            $product->stok -= $jumlah;
        }
        $product->save();

        $this->inserted++;
    }

    private function err(int $rowIndex, string $message): void
    {
        // Contoh pesan akhir: "Baris 7: Stok tidak cukup untuk 'Keyboard'. Stok saat ini: 1, diminta: 5."
        $this->errors[] = "Baris {$rowIndex}: {$message}";
        $this->skipped++;
    }
}
