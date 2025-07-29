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
        Schema::create('santri_magic_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->string('token')->unique();
            $table->timestamp('expired_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santri')->onDelete('cascade');
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri_magic_tokens');
    }
};
