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
        $table->string('author_name');
        $table->string('author_email');
        $table->string('title');
        $table->text('description');
        $table->string('web_link')->nullable();
        $table->string('image_path')->nullable();
        $table->string('file_path')->nullable();
        $table->boolean('is_approved')->default(false);
        $table->timestamps();
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
