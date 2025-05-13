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
        Schema::create('asb_objek_tahun', function (Blueprint $table) {
            $table->id();

            // FK ke struktur_asb
            $table->unsignedBigInteger('asb_id');
            $table->foreign('asb_id')->references('id')
                ->on('struktur_asb')->onDelete('cascade');

            // FK ke objek_belanja
            $table->unsignedBigInteger('objek_belanja_id');
            $table->foreign('objek_belanja_id')->references('id')
                ->on('objek_belanja')->onDelete('restrict');

            $table->year('tahun');
            $table->decimal('persentase', 6, 2)->default(0);
            // $table->decimal('jumlah_rp', 18, 2)->default(0);

            $table->timestamps();

            // mencegah duplikasi tahun–objek–ASB
            $table->unique(
                ['asb_id', 'objek_belanja_id', 'tahun'],
                'unik_asb_objek_tahun'
            );
            $table->index(['asb_id', 'tahun'], 'idx_asb_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asb_objek_tahun');
    }
};
