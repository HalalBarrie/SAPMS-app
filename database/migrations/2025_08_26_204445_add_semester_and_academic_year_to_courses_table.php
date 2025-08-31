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
    Schema::table('courses', function (Blueprint $table) {
        if (! Schema::hasColumn('courses', 'semester')) {
            $table->unsignedTinyInteger('semester')->default(1)->after('credit_hours'); // 1 or 2
        }
        if (! Schema::hasColumn('courses', 'academic_year')) {
            $table->string('academic_year', 9)->default(date('Y') . '/' . (date('Y')+1))->after('semester');
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('courses', function (Blueprint $table) {
        if (Schema::hasColumn('courses', 'semester')) {
            $table->dropColumn('semester');
        }
        if (Schema::hasColumn('courses', 'academic_year')) {
            $table->dropColumn('academic_year');
        }
    });
}

};
