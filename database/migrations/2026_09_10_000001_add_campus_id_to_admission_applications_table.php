<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->foreignId('campus_id')
                ->nullable()
                ->after('education_level')
                ->constrained('campuses')
                ->nullOnDelete();
        });

        DB::table('admission_applications')
            ->whereNotNull('school_destination')
            ->orderBy('id')
            ->chunkById(100, function ($applications) {
                foreach ($applications as $application) {
                    $campusId = DB::table('campuses')
                        ->where('title', $application->school_destination)
                        ->value('id');

                    if ($campusId) {
                        DB::table('admission_applications')
                            ->where('id', $application->id)
                            ->update(['campus_id' => $campusId]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('admission_applications', function (Blueprint $table) {
            $table->dropForeign(['campus_id']);
            $table->dropColumn('campus_id');
        });
    }
};