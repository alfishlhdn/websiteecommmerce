<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use App\Models\Kurir;
use App\Models\Review;
use App\Models\Whislist;
use App\Models\Payment_methods;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $data = [
        //     ['user_id' => 3, 'product_id' => 1, 'jumlah' => 2],
        //     ['user_id' => 3, 'product_id' => 2, 'jumlah' => 1],
        // ];

        // foreach ($data as $item) {
        //     Cart::create($item);
        // }

        // $data = [
        //     ['user_id' => 3, 'product_id' => 1],
        //     ['user_id' => 3, 'product_id' => 2],
        // ];

        // foreach ($data as $item) {
        //     Whislist::create($item);
        // }


        // Review::create([
        //     'user_id' => 3,
        //     'product_id' => 2,
        //     'rating' => 5,
        //     'komentar' => 'Produk sangat memuaskan!',
        //     'status' => 'disetujui'
        // ]);

        // Review::create([
        //     'user_id' => 3,
        //     'product_id' => 1,
        //     'rating' => 3,
        //     'komentar' => 'Barang oke, cuma pengiriman agak lama.',
        //     'status' => 'disetujui'
        // ]);

        User::create([
            'name' => 'SuperAdmin',
            'email' => 'superAdmin@agait.com',
            'phone' => '0',
            'password' => Hash::make('superAdminAgaIT'), // ganti sesuai kebutuhan
            'role' => 'superadmin',
        ]);
        User::create([
            'name' => 'Admin AGA IT',
            'email' => 'admin@agait.com',
            'phone' => '0',
            'password' => Hash::make('AdminAgaIT'), // ganti sesuai kebutuhan
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'User AGA IT',
            'email' => 'user@agait.com',
            'phone' => '0',
            'password' => Hash::make('UserAgaIT'), // ganti sesuai kebutuhan
            'role' => 'user',
        ]);
        User::create([
            'name' => 'Client AGA IT',
            'email' => 'clietn@agait.com',
            'phone' => '0',
            'password' => Hash::make('ClientAgaIT'), // ganti sesuai kebutuhan
            'role' => 'client',
        ]);

        // $kurirs = [
        //     ['name'=>'JNE','service_type'=>'REG/OKE/YES','price'=>0],
        //     ['name'=>'J&T','service_type'=>'EZ/ECO','price'=>0],
        //     ['name'=>'SiCepat','service_type'=>'REG/BEST','price'=>0],
        //     ['name'=>'Wahana','service_type'=>'REG','price'=>0],
        // ];
        // foreach($kurirs as $k) Kurir::create($k);

//         DB::table('kurirs')->insert([
//           // --- JNE ---
//             [
//                 'name' => 'JNE',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 2-5 hari',
//                 'keterangan' => 'Khusus Malang',
//                 'price' => 10000
//             ],
//             [
//                 'name' => 'JNE',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 2-5 hari',
//                 'keterangan' => 'Luar Kota (Jawa Timur)',
//                 'price' => 20000
//             ],
//             [
//                 'name' => 'JNE',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 2-5 hari',
//                 'keterangan' => 'Luar Jawa Timur (Pulau Jawa)',
//                 'price' => 30000
//             ],

//             // --- J&T ---
//             [
//                 'name' => 'J&T',
//                 'service_code' => 'EZ',
//                 'service_type' => 'Economy – 3-6 hari',
//                 'keterangan' => 'Khusus Malang',
//                 'price' => 9000
//             ],
//             [
//                 'name' => 'J&T',
//                 'service_code' => 'EZ',
//                 'service_type' => 'Economy – 3-6 hari',
//                 'keterangan' => 'Luar Kota (Jawa Timur)',
//                 'price' => 19000
//             ],
//             [
//                 'name' => 'J&T',
//                 'service_code' => 'EZ',
//                 'service_type' => 'Economy – 3-6 hari',
//                 'keterangan' => 'Luar Jawa Timur (Pulau Jawa)',
//                 'price' => 28000
//             ],

//             // --- SiCepat ---
//             [
//                 'name' => 'SiCepat',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 2-4 hari',
//                 'keterangan' => 'Khusus Malang',
//                 'price' => 11000
//             ],
//             [
//                 'name' => 'SiCepat',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 2-4 hari',
//                 'keterangan' => 'Luar Kota (Jawa Timur)',
//                 'price' => 21000
//             ],
//             [
//                 'name' => 'SiCepat',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 2-4 hari',
//                 'keterangan' => 'Luar Jawa Timur (Pulau Jawa)',
//                 'price' => 32000
//             ],

//             // --- Wahana ---
//             [
//                 'name' => 'Wahana',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 3-6 hari',
//                 'keterangan' => 'Khusus Malang',
//                 'price' => 8000
//             ],
//             [
//                 'name' => 'Wahana',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 3-6 hari',
//                 'keterangan' => 'Luar Kota (Jawa Timur)',
//                 'price' => 18000
//             ],
//             [
//                 'name' => 'Wahana',
//                 'service_code' => 'REG',
//                 'service_type' => 'Regular – 3-6 hari',
//                 'keterangan' => 'Luar Jawa Timur (Pulau Jawa)',
//                 'price' => 27000
//             ],
// ]);


        // Payment_methods::create([
        //     'name' => 'QRIS',
        //     'code' => 'qris',
        //     'description' => 'Pembayaran melalui QRIS (scan QR)',
        //     'is_active' => true
        // ]);

    }
}
