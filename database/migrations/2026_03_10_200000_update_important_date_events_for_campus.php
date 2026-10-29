<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('important_date_events', 'campus_id')) {
            Schema::table('important_date_events', function (Blueprint $table) {
                $table->foreignId('campus_id')->nullable()->constrained('campuses')->onDelete('cascade');
            });
        }

        try {
            Schema::table('important_date_events', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
            });
        } catch (\Throwable $e) {
            // Ignore when FK does not exist (can happen on sqlite/testing).
        }

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE important_date_events MODIFY branch_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE important_date_events MODIFY branch_id BIGINT UNSIGNED NOT NULL');
        }

        Schema::table('important_date_events', function (Blueprint $table) {
            try {
                $table->foreign('branch_id')->references('id')->on('important_date_branches')->onDelete('cascade');
            } catch (\Throwable $e) {
                // Ignore if already exists.
            }
            try {
                $table->dropForeign(['campus_id']);
            } catch (\Throwable $e) {
                // Ignore if FK does not exist.
            }
            if (Schema::hasColumn('important_date_events', 'campus_id')) {
                $table->dropColumn('campus_id');
            }
        });
    }
};
