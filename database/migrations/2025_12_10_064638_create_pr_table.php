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
        Schema::create('pr', function (Blueprint $table) {
            $table->integer('id_pr', true);
            $table->integer('id_user')->index('id_user');
            $table->string('pr_number', 50)->unique('pr_number');
            $table->string('status', 50)->nullable()->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->string('department', 100)->nullable();
            $table->string('division', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr');
    }
};
