<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_address_id')->nullable()->constrained('user_addresses')->nullOnDelete();
            $table->foreignId('kurir_id')->nullable()->constrained('kurirs')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();

            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('total')->default(0);

            $table->text('catatan')->nullable();

            // Status pembayaran
            $table->enum('payment_status', ['pending','waiting_confirmation','paid','cancelled'])->default('pending');

            // Status pengiriman
            $table->enum('shipping_status', ['pending','processing','shipped','delivered','cancelled'])->default('pending');

            // QRIS jika dipakai
            $table->enum('qris_source', ['generated','stored'])->nullable();
            $table->string('qris_image_path')->nullable();
            $table->text('qris_payload')->nullable();

            // Bukti bayar
            $table->string('proof_path')->nullable();
            $table->timestamp('proof_submitted_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('nomor_resi');
            $table->string('return_to')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};