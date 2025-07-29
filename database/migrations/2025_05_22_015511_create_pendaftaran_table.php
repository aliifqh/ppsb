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
        // Tabel 1: Data Santri
        Schema::create('santri', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('unit');
            $table->string('nama');
            $table->string('nisn', 10);
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('asal_sekolah');
            $table->integer('anak_ke');
            $table->integer('jumlah_saudara');
            $table->string('status_tes')->default('Menunggu Tes');
            $table->foreignId('gelombang_id')->nullable()->constrained('gelombangs')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel 2: Data Orang Tua dan Alamat
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah');
            $table->string('pekerjaan_ibu');
            $table->string('pekerjaan_ayah_lainnya')->nullable();
            $table->string('pekerjaan_ibu_lainnya')->nullable();
            $table->string('pendidikan_ayah');
            $table->string('pendidikan_ibu');
            $table->string('wa_ayah');
            $table->string('wa_ibu');
            $table->text('alamat');
            $table->string('provinsi_id');
            $table->string('kabupaten_id');
            $table->string('kecamatan_id');
            $table->string('desa_id');
            $table->string('kode_pos', 5);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel 3: File-file
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->string('pasfoto');
            $table->string('akta_lahir');
            $table->string('kartu_keluarga');
            $table->string('ijazah');
            $table->json('dokumen_pendukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('dokumen');
        Schema::dropIfExists('orang_tua');
        Schema::dropIfExists('santri');
    }
};
