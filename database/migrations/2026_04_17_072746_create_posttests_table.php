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
        Schema::create('posttests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('slug_id')->constrained('slugs')->onDelete('cascade');
    $table->string('judul');
    $table->string('file_soal')->nullable();
    $table->integer('kkm')->default(75);
    $table->timestamp('waktu_mulai')->nullable();
$table->timestamp('waktu_selesai')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posttests');
    }
};
