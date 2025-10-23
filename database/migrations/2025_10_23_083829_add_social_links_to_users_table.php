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
       Schema::table('users', function (Blueprint $table) {
        $table->string('github_link')->nullable()->after('profile_image');
        $table->string('linkedin_link')->nullable()->after('github_link');
        $table->string('instagram_link')->nullable()->after('linkedin_link');
        $table->string('portfolio_link')->nullable()->after('instagram_link');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['github_link', 'linkedin_link', 'instagram_link', 'portfolio_link']);
    });
    }
};
