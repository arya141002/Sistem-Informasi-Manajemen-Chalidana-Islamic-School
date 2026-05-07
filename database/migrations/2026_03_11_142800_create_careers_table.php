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
        Schema::create('careers', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED, AUTO_INCREMENT
            $table->timestamp('tanggal')->nullable(); // timestamp, Null: Yes
            $table->unsignedInteger('career_status')->nullable(); // int(10) UNSIGNED, Null: Yes
            $table->string('title', 255); // varchar(255), Null: No
            $table->string('title_id', 255); // varchar(255), Null: No
            $table->longText('content'); // longtext, Null: No
            $table->longText('content_id'); // longtext, Null: No
            
            $table->softDeletes(); // deleted_at (timestamp, nullable)
            $table->timestamps(); // created_at & updated_at (timestamp, nullable)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
