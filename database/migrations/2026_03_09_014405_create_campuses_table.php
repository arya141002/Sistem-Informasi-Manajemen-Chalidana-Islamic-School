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
        Schema::create('campuses', function (Blueprint $table) {
            $table->id();
            $table->integer('catcampus_id')->unsigned();
            $table->string('title', 255);
            $table->longText('NPSN');
            $table->string('school_tour', 255);
            $table->string('subject', 200);
            $table->string('slug', 255);
            $table->string('img_header', 255);
            $table->string('img_principal', 255);
            $table->string('from', 255);
            $table->string('from_id', 255);
            $table->longText('message');
            $table->longText('message_id');
            $table->longText('address');
            $table->longText('maplocation');
            $table->longText('content');
            $table->longText('content_id');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};
