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
        // Supprimer la clé étrangère vers 'categories'
        Schema::table('produits', function (Blueprint $table) {
            $table->dropForeign(['Category_id']); // Supprime la contrainte de clé étrangère
            $table->dropColumn('Category_id');   // Supprime la colonne 'Category_id'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-ajouter la colonne 'Category_id' si besoin
        Schema::table('produits', function (Blueprint $table) {
            $table->foreignId('Category_id')->constrained('categories')->onDelete('cascade'); // Ajouter la clé étrangère à 'categories'
        });
    }
};
