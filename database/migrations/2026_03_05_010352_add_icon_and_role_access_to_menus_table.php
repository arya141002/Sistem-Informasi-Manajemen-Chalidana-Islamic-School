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
    Schema::table('menus', function (Blueprint $table) {
        // Tambahkan kolom icon setelah kolom title
        $table->string('icon')->nullable()->after('title');
    });
}

public function down(): void
{
    Schema::table('menus', function (Blueprint $table) {
        // Hapus kolom jika migration di-rollback
        $table->dropColumn(['icon']);
    });
}
};
