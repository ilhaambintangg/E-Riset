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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('email');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->string('konsentrasi')->nullable()->after('research_type');
            $table->foreignId('hakim_id')->nullable()->after('konsentrasi')->constrained('hakims')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['hakim_id']);
            $table->dropColumn(['konsentrasi', 'hakim_id']);
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
