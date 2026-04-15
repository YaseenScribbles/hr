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
        Schema::create('attd_salary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal('worked_days', 5, 2);
            $table->decimal('worked_shift', 5, 2);
            $table->decimal('holiday_days', 5, 2);
            $table->decimal('absent_days', 5, 2);
            $table->decimal('wages', 10, 2);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('esi', 10, 2);
            $table->decimal('pf', 10, 2);
            $table->decimal('advance', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attd_salary');
    }
};
