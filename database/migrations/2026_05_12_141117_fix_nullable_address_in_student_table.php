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
        $table->string('address')->nullable()->change();
        $table->string('email')->nullable()->change();
        $table->string('avatar_url')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('student', function (Blueprint $table) {
        $table->string('address')->nullable(false)->change();
        $table->string('email')->nullable(false)->change();
        $table->string('avatar_url')->nullable(false)->change();
    });
}
};