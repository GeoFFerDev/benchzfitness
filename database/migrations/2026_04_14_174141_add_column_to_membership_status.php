<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_statuses', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->timestamp('last_check_in')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('membership_statuses', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'last_check_in']);
        });
    }
};