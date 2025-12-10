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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_user', true);
            $table->string('name', 100);
            $table->string('email', 100)->unique('email');
            $table->string('password');
            $table->string('badge', 50)->nullable();
            $table->string('role', 50)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('division', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
