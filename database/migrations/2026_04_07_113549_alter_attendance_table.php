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
        Schema::table('attendance', function (Blueprint $table) {
            $table->time('log_in')->nullable()->after('remarks');
            $table->time('lunch_out')->nullable()->after('log_in');
            $table->time('lunch_in')->nullable()->after('lunch_out');
            $table->time('log_out')->nullable()->after('lunch_in');
            $table->time('actual_hours')->nullable()->after('log_out');
            $table->time('ot_in')->nullable()->after('actual_hours');
            $table->time('ot_out')->nullable()->after('ot_in');
            $table->time('total_hours')->nullable()->after('ot_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropColumn([
                'log_in',
                'lunch_out',
                'lunch_in',
                'log_out',
                'actual_hours',
                'ot_in',
                'ot_out',
                'total_hours'
            ]);
        });
    }
};
