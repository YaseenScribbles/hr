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
        Schema::create('emp_nominees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emp_id')->constrained('employees')->onDelete('cascade');
            $table->string('name');
            $table->string('relationship');
            $table->boolean('residing_with')->default(true);
            $table->date('d_o_b')->nullable();
            $table->smallInteger('age')->nullable();
            $table->string('profession')->nullable();
            $table->decimal('salary')->nullable();
            $table->longText('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_nominees');
    }
};
