<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('important_date_events', function (Blueprint $table) {
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->onDelete('cascade');
            $table->dropForeign(['branch_id']);
        });

        DB::statement('ALTER TABLE important_date_events MODIFY branch_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE important_date_events MODIFY branch_id BIGINT UNSIGNED NOT NULL');

        Schema::table('important_date_events', function (Blueprint $table) {
            $table->foreign('branch_id')->references('id')->on('important_date_branches')->onDelete('cascade');
            $table->dropForeign(['campus_id']);
            $table->dropColumn('campus_id');
        });
    }
};
