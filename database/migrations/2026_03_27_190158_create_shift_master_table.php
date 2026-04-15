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
        Schema::create('shift_master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->noActionOnDelete();
            $table->string('code')->unique();
            $table->string('description')->nullable();

            // Login
            $table->time('login')->nullable();
            $table->time('login_min')->nullable();
            $table->time('login_max')->nullable();

            // Logout
            $table->time('logout')->nullable();
            $table->time('logout_min')->nullable();
            $table->time('logout_max')->nullable();

            // Lunch In
            $table->time('lunch_in')->nullable();
            $table->time('lunch_in_min')->nullable();
            $table->time('lunch_in_max')->nullable();

            // Lunch Out
            $table->time('lunch_out')->nullable();
            $table->time('lunch_out_min')->nullable();
            $table->time('lunch_out_max')->nullable();

            // OT In
            $table->time('ot_in')->nullable();
            $table->time('ot_in_min')->nullable();
            $table->time('ot_in_max')->nullable();

            // OT Out
            $table->time('ot_out')->nullable();
            $table->time('ot_out_min')->nullable();
            $table->time('ot_out_max')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_master');
    }
};
