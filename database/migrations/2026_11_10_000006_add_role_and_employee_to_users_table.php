<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin_sdm')->after('email'); // superadmin, admin_sdm, pimpinan, pegawai
            $table->foreignId('employee_id')->nullable()->after('role')->constrained('employees')->nullOnDelete();
            $table->string('avatar')->nullable()->after('employee_id');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['role', 'employee_id', 'avatar', 'is_active']);
        });
    }
};
