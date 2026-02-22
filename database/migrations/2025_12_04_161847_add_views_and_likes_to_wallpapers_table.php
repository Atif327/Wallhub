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
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->integer('views')->default(0)->after('description');
            $table->integer('likes')->default(0)->after('views');
            $table->integer('downloads')->default(0)->after('likes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->dropColumn(['views', 'likes', 'downloads']);
        });
    }
};
