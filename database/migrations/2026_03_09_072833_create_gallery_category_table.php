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
        Schema::create('studies', function (Blueprint $table) {
            // 1. id bigint(20) UNSIGNED AUTO_INCREMENT
            $table->id();

            // 2. study_id int(10) UNSIGNED (Not Null)
            $table->unsignedInteger('study_id');

            // 3. title varchar(255)
            $table->string('title', 255);

            // 4. title_id varchar(255)
            $table->string('title_id', 255);

            // 5. slug varchar(200)
            $table->string('slug', 200);

            // 6. img varchar(100) (Nullable)
            $table->string('img', 100)->nullable();

            // 7. location varchar(200)
            $table->string('location', 200);

            // 8. deleted_at (Soft Deletes)
            $table->softDeletes();

            // 9 & 10. created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_category');
    }
};
