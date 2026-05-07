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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id(); // bigint(20) UNSIGNED, AUTO_INCREMENT
            $table->unsignedInteger('campus_id')->nullable(); // int(10) UNSIGNED
            $table->string('grade', 200)->nullable(); // varchar(200)
            $table->string('student', 200)->nullable(); // varchar(200)
            $table->string('title', 200)->nullable(); // varchar(200)
            $table->string('images', 100)->nullable(); // varchar(100)
            $table->longText('content')->nullable(); // longtext
            $table->tinyInteger('status')->default(0); // tinyint(1), default 0
            $table->tinyInteger('rating')->nullable(); // tinyint(1)
            
            $table->softDeletes(); // deleted_at (timestamp)
            $table->timestamps(); // created_at & updated_at (timestamp)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial');
    }
};
