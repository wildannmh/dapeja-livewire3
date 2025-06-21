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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->foreignId('jabatan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_kerja_id')->constrained()->cascadeOnDelete();
            $table->decimal('gaji', 15, 2)->default(0);
            // Tambahkan foreign key ke user jika perlu (untuk login per pegawai)
            // $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
