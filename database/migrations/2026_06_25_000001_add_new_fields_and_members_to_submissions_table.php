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
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('recipient_position')->nullable();
            $table->string('destination_city')->nullable();
            $table->string('reference_letter_number')->nullable();
            $table->date('reference_letter_date')->nullable();
            $table->text('research_title')->nullable();
            $table->string('research_location')->nullable();
            $table->string('research_type')->nullable();
        });

        Schema::create('submission_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->string('member_name');
            $table->string('member_npm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_members');

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_position',
                'destination_city',
                'reference_letter_number',
                'reference_letter_date',
                'research_title',
                'research_location',
                'research_type'
            ]);
        });
    }
};
