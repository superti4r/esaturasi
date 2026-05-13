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
        Schema::table('student', function (Blueprint $table) {
            $table->dropUnique('student_nipd_unique');
        });
    }
 
    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->unique('nipd', 'student_nipd_unique');
        });
    }
};