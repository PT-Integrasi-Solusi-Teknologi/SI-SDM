<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competency_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('competency_categories')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('target_level')->default('Menengah'); // Dasar, Menengah, Mahir
            $table->timestamps();
        });

        Schema::create('employee_competencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('competency_id')->constrained('competencies')->cascadeOnDelete();
            $table->enum('level', ['Dasar', 'Menengah', 'Mahir'])->default('Dasar');
            $table->date('assessed_date')->nullable();
            $table->string('assessor_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('name');
            $table->string('certificate_number')->nullable();
            $table->string('issuer')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('active'); // active, expiring_soon, expired
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('employee_competencies');
        Schema::dropIfExists('competencies');
        Schema::dropIfExists('competency_categories');
    }
};
