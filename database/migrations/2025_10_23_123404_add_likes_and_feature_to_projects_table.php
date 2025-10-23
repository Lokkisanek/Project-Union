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
    Schema::table('projects', function (Blueprint $table) {
        $table->unsignedBigInteger('likes')->default(0)->after('is_approved'); // Počet lajků
        $table->boolean('is_featured')->default(false)->after('likes'); // Pro carousel
    });
}

public function down(): void
{
    Schema::table('projects', function (Blueprint $table) {
        $table->dropColumn(['likes', 'is_featured']);
    });
}
};
