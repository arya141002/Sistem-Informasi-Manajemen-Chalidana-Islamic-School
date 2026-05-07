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
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'grade')) {
                $table->dropColumn('grade');
            }

            $table->string('parent_name', 200)->nullable()->after('campus_id');
            $table->unsignedBigInteger('grade_id')->nullable()->after('parent_name');

            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign(['grade_id']);
            $table->dropColumn(['grade_id', 'parent_name']);
            $table->string('grade', 200)->nullable();
        });
    }
};
