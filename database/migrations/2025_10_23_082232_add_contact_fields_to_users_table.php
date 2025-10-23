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
        $table->string('position')->nullable()->after('is_admin'); // Např. "Vedoucí MP"
        $table->string('phone')->nullable()->after('position');    // Tel. číslo
        $table->string('profile_image')->nullable()->after('phone'); // Cesta k profilovce
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['position', 'phone', 'profile_image']);
    });
    }
};
