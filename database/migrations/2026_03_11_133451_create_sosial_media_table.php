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
        Schema::create('sosial_media', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED, AUTO_INCREMENT
            $table->string('title', 255); // varchar(255)
            $table->string('icon', 255); // varchar(255)
            $table->string('bgcolor', 100); // varchar(100)
            $table->longText('link'); // longtext
            
            $table->softDeletes(); // deleted_at (timestamp, nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sosial_media');
    }
};
