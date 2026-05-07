<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('important_date_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('program', ['primary', 'preschool']);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('important_date_branches');
    }
};
