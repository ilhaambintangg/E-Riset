<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('gender')->after('name')->nullable();
            $table->string('location')->after('title')->nullable();
            $table->string('methodology')->nullable()->change();
            $table->string('target_institution')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('location');
            $table->string('methodology')->nullable(false)->change();
            $table->string('target_institution')->nullable(false)->change();
        });
    }
};
