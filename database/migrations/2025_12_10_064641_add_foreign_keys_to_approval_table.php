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
        Schema::table('approval', function (Blueprint $table) {
            $table->foreign(['id_pr'], 'approval_ibfk_1')->references(['id_pr'])->on('pr')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_user'], 'approval_ibfk_2')->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approval', function (Blueprint $table) {
            $table->dropForeign('approval_ibfk_1');
            $table->dropForeign('approval_ibfk_2');
        });
    }
};
