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
        Schema::table('sub_categories', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère existante
            $table->dropForeign(['category_id']);
            
            // Ajouter une nouvelle contrainte avec onDelete('restrict')
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère existante
            $table->dropForeign(['category_id']);
            
            // Réintégrer la contrainte avec onDelete('cascade')
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }
};
