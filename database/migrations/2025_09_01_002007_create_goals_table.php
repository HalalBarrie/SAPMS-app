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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['semester', 'cumulative']);
            $table->decimal('target_gpa', 3, 2);
            $table->string('academic_year')->nullable();
            $table->integer('semester')->nullable();
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->timestamps();

            // A user can only have one active goal of each type
            $table->unique(['user_id', 'type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};