<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->decimal('biaya_administrasi', 12, 2);
            $table->decimal('biaya_daftar_ulang', 12, 2);
            $table->decimal('nominal_transfer_administrasi', 12, 2)->nullable();
            $table->decimal('nominal_transfer_daftar_ulang', 12, 2)->nullable();
            $table->enum('status_administrasi', ['belum_bayar', 'sudah_bayar', 'diverifikasi'])->default('belum_bayar');
            $table->enum('status_daftar_ulang', ['belum_bayar', 'sudah_bayar', 'diverifikasi'])->default('belum_bayar');
            $table->string('bukti_biaya_administrasi')->nullable();
            $table->string('bukti_biaya_daftar_ulang')->nullable();
            $table->string('bukti_biaya_administrasi_admin')->nullable();
            $table->string('bukti_biaya_daftar_ulang_admin')->nullable();
            $table->timestamp('tanggal_administrasi')->nullable();
            $table->timestamp('tanggal_daftar_ulang')->nullable();
            $table->text('keterangan_administrasi')->nullable();
            $table->text('keterangan_daftar_ulang')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
