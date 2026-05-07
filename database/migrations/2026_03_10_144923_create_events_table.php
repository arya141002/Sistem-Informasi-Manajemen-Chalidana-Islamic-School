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
        Schema::create('events', function (Blueprint $table) {
            // 1. id (BigInt UNSIGNED, Primary Key, Auto Increment)
            $table->id();

            // 2. study_id (Int UNSIGNED)
            $table->unsignedInteger('study_id');

            // 3. img (Varchar 200, Nullable)
            $table->string('img', 200)->nullable();

            // 4. tanggal (Timestamp, Nullable)
            $table->timestamp('tanggal')->nullable();

            // 5. slug (Varchar 200, Unique biasanya disarankan untuk slug)
            $table->string('slug', 200);

            // 6 & 7. title & title_id (Varchar 255)
            $table->string('title', 255);
            $table->string('title_id', 255);

            // 8 & 9. content & content_id (LongText, Nullable)
            $table->longText('content')->nullable();
            $table->longText('content_id')->nullable();

            // 10. deleted_at (Timestamp untuk Soft Deletes)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
