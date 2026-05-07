<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('important_date_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('important_date_branches')->onDelete('cascade');
            $table->date('event_date');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('important_date_events');
    }
};
