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
        Schema::table('member_attendance_logs', function (Blueprint $table) {
            // Links to the users table
            // using constrained() tells Laravel to look at the 'users' table by default
            $table->foreignId('user_id')
                  ->after('id')
                  ->constrained()
                  ->cascadeOnDelete();

            // The specific column for your check-in timestamp
            $table->timestamp('logged_at')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_attendance_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'logged_at']);
        });
    }
};
