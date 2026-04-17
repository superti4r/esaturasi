<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_posttests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('posttest_id')
                ->constrained('posttests')
                ->cascadeOnDelete();

            $table->foreignId('soal_posttest_id')
                ->constrained('soal_posttests')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('student')
                ->cascadeOnDelete();

            $table->string('jawaban_siswa');
            $table->boolean('is_benar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_posttests');
    }
};