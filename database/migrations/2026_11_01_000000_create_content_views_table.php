<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_views', function (Blueprint $table) {
            $table->id();
            $table->string('content_type', 80);
            $table->unsignedBigInteger('content_id');
            $table->char('ip_hash', 64);
            $table->date('viewed_on');
            $table->timestamps();

            $table->unique(['content_type', 'content_id', 'ip_hash', 'viewed_on'], 'content_views_daily_unique');
            $table->index(['content_type', 'content_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_views');
    }
};
