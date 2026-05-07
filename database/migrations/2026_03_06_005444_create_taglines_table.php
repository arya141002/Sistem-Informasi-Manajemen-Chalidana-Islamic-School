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
        Schema::create('taglines', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('title');      // Title (EN)
            $table->string('title_id');   // Title (ID)
            $table->string('jargon')->nullable();    // Jargon (EN)
            $table->string('jargon_id')->nullable();
            $table->text('content');      // Content (EN)
            $table->text('content_id');   // Content (ID)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taglines');
    }
};
