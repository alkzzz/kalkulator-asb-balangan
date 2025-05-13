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
        Schema::create('cost_drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('struktur_asb_id')->constrained('struktur_asb')->onDelete('cascade');
            $table->string('label'); // Contoh: Jumlah Peserta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_drivers');
    }
};
