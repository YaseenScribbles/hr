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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('sal_type')->nullable()->after('des_id');
            $table->decimal('salary', 15, 2)->nullable()->after('sal_type');
            $table->boolean('esi_eligible')->default(true)->after('salary');
            $table->string('esi_number')->nullable()->after('esi_eligible');
            $table->string('pf_number')->nullable()->after('esi_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('sal_type');
            $table->dropColumn('salary');
        });
    }
};
