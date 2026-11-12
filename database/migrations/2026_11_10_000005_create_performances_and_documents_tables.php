<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('year');
            $table->string('period_type')->default('Tahunan'); // Semester 1, Semester 2, Tahunan, Q1, Q2...
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('performance_periods')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->default(85.00);
            $table->string('rating')->default('Baik'); // Sangat Baik, Baik, Cukup, Kurang
            $table->string('evaluator_name')->nullable();
            $table->text('achievements')->nullable();
            $table->text('feedback')->nullable();
            $table->date('review_date')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('document_type'); // SK Pengangkatan, Kontrak Kerja, Ijazah, KTP, Sertifikat, Portofolio, Evaluasi
            $table->string('title');
            $table->string('document_number')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_size')->nullable();
            $table->string('file_type')->default('pdf');
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('action'); // CREATE, UPDATE, DELETE, APPROVE, REJECT
            $table->string('module'); // Pegawai, Cuti, Absensi, Kompetensi, Organisasi
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('performance_periods');
    }
};
