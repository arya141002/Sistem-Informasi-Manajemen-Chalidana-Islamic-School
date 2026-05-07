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
        Schema::create('_cis_in_number', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED, AUTO_INCREMENT
            $table->string('icon', 200); // varchar(200)
            $table->string('title', 200); // varchar(200)
            $table->string('title_id', 200); // varchar(200)
            $table->string('number', 200); // varchar(200)
            $table->longText('content'); // longtext
            
            $table->softDeletes(); // deleted_at (timestamp, nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_cis_in_number');
    }
};
