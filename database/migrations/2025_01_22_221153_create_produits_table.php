<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('produits', function (Blueprint $table) {
        $table->id();
        $table->string('UID')->unique();
        $table->string('Product');
        $table->text('Description');
        $table->foreignId('Category_id')->constrained('categories')->onDelete('cascade'); // Relier à la table categories
        $table->foreignId('SubCategory_id')->constrained('sub_categories')->onDelete('cascade'); // Relier à la table sub_categories
        $table->string('Brand');
        $table->decimal('OldPrice', 8, 2)->nullable();
        $table->decimal('Price', 8, 2);
        $table->integer('Stock');
        $table->float('Rating', 2, 1)->default(0);
        $table->boolean('HotProduct')->default(false);
        $table->boolean('BestSeller')->default(false);
        $table->boolean('TopRated')->default(false);
        $table->integer('Order')->default(0);
        $table->integer('Sales')->default(0);
        $table->boolean('IsFeatured')->default(false);
        $table->json('Image')->nullable();
        $table->json('Tags')->nullable();
        $table->json('Variants')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
