<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('color')->default('emerald');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('level')->default('Staff'); // Pimpinan, Koordinator, Staff, Pelaksana, Teknisi
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('employment_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('badge_color')->default('green');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('employment_statuses');
        Schema::dropIfExists('employment_types');
    }
};
