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
        Schema::create('about_schools', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_id');
            $table->text('content');
            $table->text('content_id');
            $table->string('img')->nullable(); // Menyimpan nama file atau path gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_scool');
    }
};
