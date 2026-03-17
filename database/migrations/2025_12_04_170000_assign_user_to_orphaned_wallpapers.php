<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Wallpaper;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the first admin or any user to assign old wallpapers
        $defaultUser = User::first();
        
        if ($defaultUser) {
            // Assign all wallpapers without a user to the first user
            Wallpaper::whereNull('user_id')->update(['user_id' => $defaultUser->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the change
        Wallpaper::whereNull('user_id')->update(['user_id' => null]);
    }
};
