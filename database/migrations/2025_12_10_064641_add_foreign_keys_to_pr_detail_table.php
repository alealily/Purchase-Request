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
        Schema::table('pr_detail', function (Blueprint $table) {
            $table->foreign(['id_pr'], 'pr_detail_ibfk_1')->references(['id_pr'])->on('pr')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_user'], 'pr_detail_ibfk_2')->references(['id_user'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['id_supplier'], 'pr_detail_ibfk_3')->references(['id_supplier'])->on('supplier')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pr_detail', function (Blueprint $table) {
            $table->dropForeign('pr_detail_ibfk_1');
            $table->dropForeign('pr_detail_ibfk_2');
            $table->dropForeign('pr_detail_ibfk_3');
        });
    }
};
