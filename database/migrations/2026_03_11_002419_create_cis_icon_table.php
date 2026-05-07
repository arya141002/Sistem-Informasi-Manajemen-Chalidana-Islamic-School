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
        Schema::create('cis_icon', function (Blueprint $table) {
            $table->id(); 

            // 2. title varchar(255) utf8mb4_unicode_ci
            $table->string('title', 255);

            // 3. file varchar(255) utf8mb4_unicode_ci
            $table->string('file', 255);

            // 4. deleted_at timestamp NULL (Soft Deletes)
            $table->softDeletes();

            // 5 & 6. created_at & updated_at timestamp NULL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cis_icon');
    }
};
