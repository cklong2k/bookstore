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
        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 127)->nullable();
            $table->string('author', 127)->nullable();
            $table->date('publicationDate')->index();
            $table->string('category', 127)->nullable();
            $table->float('price', 8, 2, true)->default(0);
            $table->integer('quantity', false, true)->default(0);
            $table->json('images')->nullable();
            $table->integer('creator')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
