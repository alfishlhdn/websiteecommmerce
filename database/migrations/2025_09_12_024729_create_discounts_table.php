<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama diskon atau voucher
            $table->enum('type', ['product', 'voucher']); // Tipe: produk atau voucher
            $table->enum('discount_type', ['percent', 'nominal', 'free_shipping', 'shipping_discount'])->nullable();
            $table->decimal('value', 12, 2)->nullable(); // Nominal atau persen
            $table->unsignedBigInteger('product_id')->nullable(); // Hanya untuk diskon produk
            $table->unsignedBigInteger('user_id')->nullable(); // Khusus voucher
            $table->date('expired_at')->nullable(); // Berlaku untuk voucher
            $table->boolean('status')->default(true); // Aktif atau tidak
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};