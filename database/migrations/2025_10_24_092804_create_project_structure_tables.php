<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabulka KATEGORIÍ (Musí být první)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('type')->default('web');
            $table->timestamps();
        });

        // 2. ÚPRAVA ZÁKLADNÍ TABULKY PROJECTS (Která se vytvoří ihned poté, co dokončíme tuto)
        // POZOR: Tato tabulka se MUSÍ vytvořit. Jelikož ji Laravel negeneruje, 
        // musíme ji zde definovat sami. Použijeme stejné schéma jako v MIGRACI 3
        // a zajistíme, že má novější čas než 0001_01_01_000002.
        
        // Vytvoříme novou tabulku PROJECTS, protože ta se vytvořila jen s holými sloupci
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_email');
            $table->string('title');
            $table->text('description');

            $table->foreignId('category_id')->nullable()->constrained(); // Cizí klíč
            
            $table->string('web_link')->nullable();
            $table->string('file_path')->nullable(); // Hlavní soubor
            $table->string('main_image')->nullable(); // Hlavní náhled
            
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('likes')->default(0);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });


        // 3. Tabulka GALERIE (Mnoho-ku-jednomu)
        Schema::create('project_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('path'); 
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_gallery');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('categories');
    }
};