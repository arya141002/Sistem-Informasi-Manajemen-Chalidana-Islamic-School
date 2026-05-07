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
        Schema::create('images', function (Blueprint $table) {
            // 1. id bigint(20) UNSIGNED AUTO_INCREMENT
            $table->id();

            // 2. img_id int(10) UNSIGNED (Nullable)
            $table->unsignedInteger('img_id')->nullable();

            // 3. img varchar(200) (Nullable)
            $table->string('img', 200)->nullable();

            // 4. title varchar(255) (Not Null)
            $table->string('title', 255);

            // 5. title_id varchar(255) (Not Null)
            $table->string('title_id', 255);

            // 6. deleted_at (Soft Deletes)
            $table->softDeletes();

            // 7 & 8. created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery');
    }
};
