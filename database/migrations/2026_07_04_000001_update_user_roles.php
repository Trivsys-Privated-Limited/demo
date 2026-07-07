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
        // Add parent_id to users table for restaurant admin assignment
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
                $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
            }
        });

        // Update role enum
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });

        // Update existing roles
        \DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);
        \DB::table('users')->where('role', 'restaurant')->update(['role' => 'restaurant_admin']);
        \DB::table('users')->where('role', 'kichan')->update(['role' => 'restaurant_user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
