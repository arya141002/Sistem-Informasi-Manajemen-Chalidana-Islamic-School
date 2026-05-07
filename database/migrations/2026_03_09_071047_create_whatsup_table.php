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
        Schema::create('news', function (Blueprint $table) {
            // 1. id bigint(20) UNSIGNED AUTO_INCREMENT
            $table->id(); 

            // 2. news_id int(10) UNSIGNED (Nullable)
            $table->unsignedInteger('news_id')->nullable();

            // 3. tanggal timestamp (Nullable)
            $table->timestamp('tanggal')->nullable();

            // 4. slug varchar(200)
            $table->string('slug', 200);

            // 5. img varchar(200) (Nullable)
            $table->string('img', 200)->nullable();

            // 6. title varchar(255)
            $table->string('title', 255);

            // 7. title_id varchar(255)
            $table->string('title_id', 255);

            // 8. content longtext (Nullable)
            $table->longText('content')->nullable();

            // 9. content_id longtext (Nullable)
            $table->longText('content_id')->nullable();

            // 10. deleted_at (Soft Deletes)
            $table->softDeletes();

            // 11 & 12. created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsup');
    }
};
