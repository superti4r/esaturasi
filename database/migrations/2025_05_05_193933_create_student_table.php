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
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->string('nisn');
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->foreignId('classroom_id')->constrained('classroom')->onDelete('cascade');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('address');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar_url')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
