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
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle')->nullable(); // contoh: // About Us //
            $table->string('title')->nullable(); // contoh: CarServ Is The Best Place For Your Auto Care
            $table->text('description')->nullable(); // paragraf deskripsi
            $table->string('image')->nullable(); // contoh: landing/img/about.jpg
            $table->integer('experience_years')->default(0);
            $table->string('experience_label')->default('Experience');
            $table->string('button_text')->nullable()->default('Read More');
            $table->string('button_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_sections');
    }
};
