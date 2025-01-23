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
        Schema::create('reviews_data', function (Blueprint $table) {
            $table->id();
            $table->string('reviewer');
            $table->foreignId('product_id')->constrained('produits')->onDelete('cascade'); // Assuming "produits" is the product table
            $table->integer('rating');
            $table->text('comment');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews_data');
    }
};
