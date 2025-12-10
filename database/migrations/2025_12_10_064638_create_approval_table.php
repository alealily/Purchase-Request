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
        Schema::create('approval', function (Blueprint $table) {
            $table->integer('id_approval', true);
            $table->integer('id_pr')->index('id_pr');
            $table->integer('id_user')->index('id_user');
            $table->dateTime('approval_date')->nullable()->useCurrent();
            $table->string('approval_status', 50);
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval');
    }
};
