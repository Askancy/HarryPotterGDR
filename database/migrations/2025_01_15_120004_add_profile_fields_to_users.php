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
            $table->boolean('profile_public')->default(true)->after('biography');
            $table->boolean('show_inventory')->default(true)->after('profile_public');
            $table->boolean('show_stats')->default(true)->after('show_inventory');
            $table->string('profile_title')->nullable()->after('show_stats'); // Es: "Il Grande Mago"
            $table->integer('profile_views')->default(0)->after('profile_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_public', 'show_inventory', 'show_stats', 'profile_title', 'profile_views']);
        });
    }
};
