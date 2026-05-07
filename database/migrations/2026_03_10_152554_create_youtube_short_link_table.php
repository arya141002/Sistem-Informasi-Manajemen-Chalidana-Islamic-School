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
        Schema::create('youtube_shorts', function (Blueprint $table) {
            // 1. id (BigInt UNSIGNED, Auto Increment)
            $table->id();

            // 2. campus_id (BigInt UNSIGNED)
            // Menggunakan foreignId agar standar dengan Laravel modern
            $table->foreignId('campus_id')->constrained('campuses')->onDelete('cascade');

            // 3. youtube_short (Varchar 255)
            $table->string('youtube_short', 255);

            // 4, 5. created_at & updated_at 
            // Laravel secara default mengatur ini menjadi nullable, 
            // tapi akan otomatis terisi saat insert/update.
            $table->timestamps();

            // 6. deleted_at (Soft Deletes)
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_short_link');
    }
};
