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
        Schema::create('cis_update', function (Blueprint $table) {
            $table->id(); // int(10) UNSIGNED AUTO_INCREMENT
            $table->string('title', 250);
            $table->string('title_id', 250);
            $table->string('img', 250);
            $table->enum('status', ['0', '1']);
            $table->string('url', 250);
            
            $table->softDeletes(); // deleted_at (datetime, nullable)
            $table->timestamps();  // created_at & updated_at (datetime)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cis_update');
    }
};
