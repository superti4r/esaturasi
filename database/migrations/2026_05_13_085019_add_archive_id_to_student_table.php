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
            $table->foreignId('archive_id')->nullable()->constrained('archive')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            $table->dropForeign(['archive_id']);
            $table->dropColumn('archive_id');
        });
    }
};