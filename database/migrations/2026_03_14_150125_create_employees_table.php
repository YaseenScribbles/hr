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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('actual_emp_id')->unique();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('gender');
            $table->date('d_o_j');
            $table->date('d_o_l')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('audit')->default(true);
            $table->foreignId('company_id')->constrained();
            $table->foreignId('dept_id')->constrained('departments');
            $table->foreignId('cat_id')->constrained('categories');
            $table->foreignId('des_id')->constrained('designations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
