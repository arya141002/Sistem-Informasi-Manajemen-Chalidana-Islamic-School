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
        Schema::create('teacher_and_staff', function (Blueprint $table) {
            $table->id();
            $table->string('img')->nullable();
            $table->string('campus_id');
            $table->enum('role', ['teacher', 'staff', 'ob', 'principal']);
            $table->longText('content')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_and_staff');
    }
};
