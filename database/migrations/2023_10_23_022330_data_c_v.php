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
        Schema::create('datacvs', function (Blueprint $table) {
            $table->id();
            $table->string('Nama')->unique();
            $table->string('Gender');
            $table->String('Umur');
            $table->string('Gelar');
            $table->text('Latar belakang')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datacvs');
        Schema::dropIfExists('datacvss');
    }
};
