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
        if (!Schema::hasColumn('taglines', 'jargon')) {
            Schema::table('taglines', function (Blueprint $table) {
                $table->string('jargon')->nullable()->after('content_id');
            });
        }

        if (!Schema::hasColumn('taglines', 'jargon_id')) {
            Schema::table('taglines', function (Blueprint $table) {
                $table->string('jargon_id')->nullable()->after('jargon');
            });
        }
    }

    public function down(): void
    {
        Schema::table('taglines', function (Blueprint $table) {
            if (Schema::hasColumn('taglines', 'jargon')) {
                $table->dropColumn(['jargon']);
            }
            if (Schema::hasColumn('taglines', 'jargon_id')) {
                $table->dropColumn(['jargon_id']);
            }
        });
    }
};
