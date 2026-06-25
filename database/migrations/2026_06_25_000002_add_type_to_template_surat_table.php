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
        Schema::table('template_surat', function (Blueprint $table) {
            $table->string('type')->default('individu')->after('file_path'); // 'individu' or 'kelompok'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_surat', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
