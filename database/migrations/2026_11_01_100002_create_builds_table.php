<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('user_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->index()->constrained('categories')->restrictOnDelete();
            $table->unsignedInteger('total_price');
            $table->string('compatibility_status', 15); // green, yellow, red
            $table->text('compatibility_message');
            $table->boolean('is_blocked')->default(false);
            $table->timestamps();
        });

        Schema::create('build_component', function (Blueprint $table) {
            $table->id();
            $table->foreignId('build_id')->index()->constrained('builds')->cascadeOnDelete();
            $table->foreignId('component_id')->index()->constrained('components')->restrictOnDelete();
            $table->unique(['build_id', 'component_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_component');
        Schema::dropIfExists('builds');
    }
};
