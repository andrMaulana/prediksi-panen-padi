<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('actuals', function (Blueprint $table) {
            $table->id();
            $table->date('tahun');
            $table->string('kecamatan');
            $table->float('luas_lahan');
            $table->float('produksi');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `actuals` CHANGE `updated_at` `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;");
    }

    public function down(): void
    {
        Schema::dropIfExists('actuals');
    }
};
