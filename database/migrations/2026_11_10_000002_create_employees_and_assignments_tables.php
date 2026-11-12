<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->default('Laki-laki');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable();
            $table->foreignId('employment_type_id')->nullable()->constrained('employment_types')->nullOnDelete();
            $table->foreignId('employment_status_id')->nullable()->constrained('employment_statuses')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained('job_positions')->nullOnDelete();
            $table->string('work_model')->default('Hybrid'); // WFO, Hybrid, Remote, Shift
            $table->date('join_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('base_salary', 14, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('employee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('job_position_id')->nullable()->constrained('job_positions')->nullOnDelete();
            $table->string('assignment_type')->default('Penempatan Awal'); // Penempatan Awal, Mutasi, Promosi, Penugasan Khusus
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('sk_number')->nullable();
            $table->date('sk_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_assignments');
        Schema::dropIfExists('employees');
    }
};
