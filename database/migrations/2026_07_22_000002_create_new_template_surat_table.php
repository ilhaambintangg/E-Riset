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
        Schema::dropIfExists('template_surat');

        Schema::create('template_surat', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('institution_type', 10); // 'PT' or 'PN'
            $table->string('template_type', 20); // 'individu' or 'kelompok'
            $table->string('file_path');
            $table->integer('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->foreignId('uploaded_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_surat');
    }
};
