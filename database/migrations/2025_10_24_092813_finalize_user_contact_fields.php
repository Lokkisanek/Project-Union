<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Úpravy tabulky USERS
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
            
            // Kontaktní pole
            $table->string('position')->nullable()->after('is_admin');
            $table->string('phone')->nullable()->after('position');
            $table->string('profile_image')->nullable()->after('phone');
            
            // Sociální odkazy
            $table->string('github_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('portfolio_link')->nullable();
            
            // Viditelnost
            $table->boolean('show_on_contacts')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin', 'position', 'phone', 'profile_image', 
                'github_link', 'linkedin_link', 'instagram_link', 
                'portfolio_link', 'show_on_contacts'
            ]);
        });
    }
};