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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // login, create, update, delete
            $table->string('subject_type')->nullable(); // Model yang dimodifikasi (Santri, Gelombang, dll)
            $table->unsignedBigInteger('subject_id')->nullable(); // ID dari model yang dimodifikasi
            $table->text('description');
            $table->json('properties')->nullable(); // Menyimpan detail perubahan
            $table->boolean('read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
