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
            // Add level column for approval hierarchy (1, 2, 3, 4)
            $table->integer('level')
                  ->default(1)
                  ->after('id_user')
                  ->comment('Approval level: 1=Head Dept, 2=Head Div, 3=GM, 4=President Dir');
            
            // Add timestamps for audit trail
            $table->timestamps(); // creates created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approval', function (Blueprint $table) {
            $table->dropColumn(['level', 'created_at', 'updated_at']);
        });
    }
};
