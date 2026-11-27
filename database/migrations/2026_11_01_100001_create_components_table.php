<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20)->index(); // cpu, motherboard, gpu, ram, psu, case
            $table->string('name');
            $table->string('socket', 30)->nullable(); // AM4, AM5, LGA1700
            $table->string('ram_type', 15)->nullable(); // DDR4, DDR5
            $table->unsignedInteger('power_w')->default(0); // потребление или мощность БП
            $table->unsignedTinyInteger('tier')->default(3); // уровень от 1 до 5
            $table->unsignedInteger('price'); // цена в рублях
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
