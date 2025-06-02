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
        Schema::create('riwayat_belanja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skpd_id');
            $table->unsignedBigInteger('asb_id');
            $table->unsignedBigInteger('objek_belanja_id');
            $table->smallInteger('tahun');
            $table->decimal('persentase', 5, 2);
            $table->unsignedBigInteger('nilai_rupiah');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->foreign('skpd_id')->references('id')->on('skpd')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('asb_id')->references('id')->on('struktur_asb');
            $table->foreign('objek_belanja_id')->references('id')->on('objek_belanja')->onUpdate('cascade')->onDelete('restrict');

            $table->index('tahun');
            $table->index('skpd_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_belanja');
    }
};
