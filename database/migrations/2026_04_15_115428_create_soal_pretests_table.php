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
        Schema::create('soal_pretests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pretest_id')->constrained()->cascadeOnDelete();
    $table->text('soal');
    $table->string('opsi_a');
    $table->string('opsi_b');
    $table->string('opsi_c');
    $table->string('opsi_d');
    $table->string('jawaban');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_pretests');
    }
};
