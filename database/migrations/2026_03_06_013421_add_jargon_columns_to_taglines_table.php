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
        Schema::table('taglines', function (Blueprint $table) {
            // Menambahkan kolom baru setelah kolom 'content_id'
            $table->string('jargon')->nullable()->after('content_id');
            $table->string('jargon_id')->nullable()->after('jargon');
        });
    }

    public function down(): void
    {
        Schema::table('taglines', function (Blueprint $table) {
            $table->dropColumn(['jargon', 'jargon_id']);
        });
    }
};
