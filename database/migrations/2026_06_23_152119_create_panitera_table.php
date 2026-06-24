<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panitera', function (Blueprint $table) {
            $table->id();
            $table->string('nama_panitera');
            $table->string('nip')->unique();
            $table->string('jabatan');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panitera');
    }
};
