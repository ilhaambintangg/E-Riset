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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('name');
            $table->string('nim')->nullable();
            $table->string('university');
            $table->string('faculty');
            $table->string('study_program');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('title');
            $table->string('target_institution');
            $table->text('purpose');
            $table->string('methodology');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('current_status')->default('Menunggu Verifikasi');
            $table->text('admin_notes')->nullable();
            $table->string('permit_file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
