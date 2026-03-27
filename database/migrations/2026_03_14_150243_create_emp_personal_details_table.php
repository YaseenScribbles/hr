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
        Schema::create('emp_personal_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade');
            $table->string('img_path')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('d_o_b')->nullable();
            $table->smallInteger('age')->nullable();
            $table->longText('present_address')->nullable();
            $table->longText('permanent_address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('religion')->nullable();
            $table->boolean('physically_challenged')->default(false);
            $table->string('if_yes_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_personal_details');
    }
};
