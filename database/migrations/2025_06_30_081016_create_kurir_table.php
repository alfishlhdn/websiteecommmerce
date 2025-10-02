<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kurirs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // JNE, J&T
            $table->string('service_code')->nullable();  // Kode layanan: REG, YES, EZ
            $table->string('service_type')->nullable();  // Deskripsi layanan
            $table->string('keterangan')->nullable();      // Area cakupan (Malang / Jawa Timur / Pulau Jawa)
            $table->decimal('price', 10, 2)->nullable(); // estimasi biaya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurirs');
    }
};