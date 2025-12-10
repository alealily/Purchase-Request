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
        Schema::create('pr_detail', function (Blueprint $table) {
            $table->integer('id_pr_detail', true);
            $table->integer('id_pr')->index('id_pr');
            $table->integer('id_user')->index('id_user');
            $table->integer('id_supplier')->index('id_supplier');
            $table->string('uom', 50)->nullable();
            $table->string('quotation_file')->nullable();
            $table->decimal('total_cost', 10)->nullable();
            $table->decimal('unit_price', 10)->nullable();
            $table->integer('quantity')->nullable();
            $table->string('currency_code', 10)->nullable();
            $table->text('material_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_detail');
    }
};
