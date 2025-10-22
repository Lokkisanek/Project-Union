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
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // Kdo projekt nahrál
        $table->string('title');               // Název projektu
        $table->text('description');           // Delší popis
        $table->string('file_path');           // Cesta k uloženému souboru
        $table->timestamps();                  // Sloupce created_at a updated_at

        // Toto říká: sloupec 'user_id' je cizí klíč,
        // který se odkazuje na sloupec 'id' v tabulce 'users'.
        $table->foreign('user_id')->references('id')->on('users');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
