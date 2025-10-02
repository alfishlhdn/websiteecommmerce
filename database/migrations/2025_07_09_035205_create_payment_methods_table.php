<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: "QRIS", "Bank Transfer"
            $table->string('code')->unique(); // contoh: "qris", "bca", "cod"
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            // Field tambahan khusus QRIS
            $table->string('qris_image_path')->nullable();
            $table->text('qris_payload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('payment_methods');
    }
};
