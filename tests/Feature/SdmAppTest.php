<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\Competency;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\EmployeeCompetency;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\JobPosition;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\PerformancePeriod;
use App\Models\PerformanceReview;
use App\Models\User;
use Database\Seeders\SdmSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SdmAppTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SdmSeeder::class);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SI-SDM Asrama Haji');
        $response->assertSee('Admin SDM');
    }

    public function test_user_can_switch_role_and_authenticate(): void
    {
        $response = $this->get(route('switch-role', 'admin_sdm'));
        $response->assertRedirect();
        $this->assertAuthenticated();
        $this->assertEquals('admin_sdm', auth()->user()->role);
    }

    public function test_dashboard_displays_real_metrics_and_widgets(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Kendali Sumber Daya Manusia');
        $response->assertSee('Total Pegawai');
        $response->assertSee('Approval Cuti');
        $response->assertSee('Rata-rata Kinerja');
    }

    public function test_employee_directory_listing_and_filtering(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $response = $this->actingAs($user)->get(route('employees.index', ['search' => 'CRV-2354']));

        $response->assertStatus(200);
        $response->assertSee('Direktori Data Pegawai');
        $response->assertSee('CRV-2354'); // Featured employee code
    }

    public function test_employee_details_page_renders_faithfully(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $employee = Employee::first();

        $response = $this->actingAs($user)->get(route('employees.show', $employee));

        $response->assertStatus(200);
        $response->assertSee($employee->name);
        $response->assertSee($employee->employee_code);
        $response->assertSee('Informasi Pribadi');
        $response->assertSee('Semua Cuti');
        $response->assertSee('Tinjauan Performa');
    }

    public function test_can_create_new_employee_with_automatic_initial_assignment(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $dept = Department::first();
        $pos = JobPosition::first();
        $type = EmploymentType::first();
        $status = EmploymentStatus::first();

        $response = $this->actingAs($user)->post(route('employees.store'), [
            'name' => 'Dr. H. Faisal Rahman, M.Ag.',
            'gender' => 'Laki-laki',
            'email' => 'faisal.rahman@asramahaji.go.id',
            'phone' => '+62 811-9988-7766',
            'department_id' => $dept->id,
            'job_position_id' => $pos->id,
            'employment_type_id' => $type->id,
            'employment_status_id' => $status->id,
            'work_model' => 'WFO',
            'join_date' => '2026-03-01',
            'base_salary' => 9000000,
        ]);

        $createdEmp = Employee::where('name', 'Dr. H. Faisal Rahman, M.Ag.')->first();
        $this->assertNotNull($createdEmp);

        $response->assertRedirect(route('employees.show', $createdEmp));

        // Verifikasi riwayat penempatan awal otomatis tercatat
        $assignment = EmployeeAssignment::where('employee_id', $createdEmp->id)->first();
        $this->assertNotNull($assignment);
        $this->assertEquals('Penempatan Awal', $assignment->assignment_type);
    }

    public function test_employee_mutation_creates_new_assignment_history(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $employee = Employee::first();
        $newDept = Department::where('id', '!=', $employee->department_id)->first();

        $response = $this->actingAs($user)->put(route('employees.update', $employee), [
            'name' => $employee->name,
            'employee_code' => $employee->employee_code,
            'gender' => $employee->gender,
            'email' => $employee->email,
            'department_id' => $newDept->id,
            'job_position_id' => $employee->job_position_id,
            'employment_type_id' => $employee->employment_type_id,
            'employment_status_id' => $employee->employment_status_id,
            'work_model' => $employee->work_model,
            'join_date' => $employee->join_date->format('Y-m-d'),
            'base_salary' => $employee->base_salary,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('employees.show', $employee));

        // Pastikan ada assignment baru bertipe Mutasi
        $latestAssignment = EmployeeAssignment::where('employee_id', $employee->id)
            ->where('department_id', $newDept->id)
            ->first();
        $this->assertNotNull($latestAssignment);
        $this->assertStringContainsString('Mutasi', $latestAssignment->assignment_type);
    }

    public function test_leave_approval_workflow_updates_balance(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $leave = LeaveRequest::where('status', 'pending')->first();
        $this->assertNotNull($leave);

        $balanceBefore = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->first();

        $usedBefore = $balanceBefore ? $balanceBefore->used_days : 0;

        $response = $this->actingAs($user)->post(route('leaves.approve', $leave), [
            'approver_notes' => 'Disetujui. Silakan laksanakan tugas piket pengganti.',
        ]);

        $response->assertRedirect();

        $leave->refresh();
        $this->assertEquals('approved', $leave->status);

        $balanceAfter = LeaveBalance::where('employee_id', $leave->employee_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->first();

        $this->assertEquals($usedBefore + $leave->duration_days, $balanceAfter->used_days);
    }

    public function test_leave_rejection_workflow(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $leave = LeaveRequest::where('status', 'pending')->first();
        $this->assertNotNull($leave);

        $response = $this->actingAs($user)->post(route('leaves.reject', $leave), [
            'approver_notes' => 'Permohonan ditolak karena masa puncak kedatangan kloter 1.',
        ]);

        $response->assertRedirect();

        $leave->refresh();
        $this->assertEquals('rejected', $leave->status);
        $this->assertEquals('Permohonan ditolak karena masa puncak kedatangan kloter 1.', $leave->approver_notes);
    }

    public function test_attendance_recording(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $employee = Employee::first();

        $response = $this->actingAs($user)->post(route('attendance.store'), [
            'employee_id' => $employee->id,
            'date' => '2026-11-20',
            'clock_in' => '07:45',
            'clock_out' => '16:30',
            'status' => 'hadir',
            'notes' => 'Presensi dinas asrama haji',
        ]);

        $response->assertRedirect();

        $att = AttendanceRecord::where('employee_id', $employee->id)
            ->where('date', '2026-11-20')
            ->first();

        $this->assertNotNull($att);
        $this->assertEquals('hadir', $att->status);
    }

    public function test_competency_mapping_and_certification(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $employee = Employee::first();
        $comp = Competency::first();

        $response = $this->actingAs($user)->post(route('competencies.assign'), [
            'employee_id' => $employee->id,
            'competency_id' => $comp->id,
            'level' => 'Mahir',
            'assessed_date' => '2026-06-15',
            'assessor_name' => 'Tim Penguji BNSP',
            'notes' => 'Lulus ujian sertifikasi level mahir',
        ]);

        $response->assertRedirect();

        $assigned = EmployeeCompetency::where('employee_id', $employee->id)
            ->where('competency_id', $comp->id)
            ->first();

        $this->assertNotNull($assigned);
        $this->assertEquals('Mahir', $assigned->level);
    }

    public function test_performance_evaluation_submission(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $employee = Employee::first();
        $period = PerformancePeriod::first();

        $response = $this->actingAs($user)->post(route('performance.store'), [
            'employee_id' => $employee->id,
            'period_id' => $period->id,
            'score' => 95.5,
            'evaluator_name' => 'Drs. H. Ahmad Fauzi, M.M.',
            'achievements' => 'Kinerja luar biasa dalam kepemimpinan layanan jamaah lansia',
            'feedback' => 'Dipertahankan untuk menjadi teladan UPT',
        ]);

        $response->assertRedirect();

        $review = PerformanceReview::where('employee_id', $employee->id)
            ->where('period_id', $period->id)
            ->first();

        $this->assertNotNull($review);
        $this->assertEquals(95.5, (float) $review->score);
        $this->assertEquals('Sangat Baik', $review->rating);
    }

    public function test_official_print_view_renders(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $response = $this->actingAs($user)->get(route('reports.print'));

        $response->assertStatus(200);
        $response->assertSee('Kementerian Agama Republik Indonesia');
        $response->assertSee('Balai Unit Pelaksana Teknis (UPT) Asrama Haji Terpadu');
        $response->assertSee('Drs. H. Ahmad Fauzi, M.M.');
    }

    public function test_organization_department_and_position_crud(): void
    {
        $user = User::where('role', 'admin_sdm')->first();

        // 1. Create Department
        $response = $this->actingAs($user)->post(route('organization.departments.store'), [
            'code' => 'UNIT-TEST',
            'name' => 'Seksi Pengujian Mutu',
            'manager_name' => 'Dr. Mutu',
            'description' => 'Unit uji kualitas layanan',
        ]);
        $response->assertRedirect();
        $dept = Department::where('code', 'UNIT-TEST')->first();
        $this->assertNotNull($dept);

        // 2. Update Department
        $response = $this->actingAs($user)->put(route('organization.departments.update', $dept), [
            'code' => 'UNIT-TEST',
            'name' => 'Seksi Pengujian Mutu Terpadu',
            'manager_name' => 'Dr. Mutu Baru',
            'description' => 'Unit uji kualitas layanan update',
        ]);
        $response->assertRedirect();
        $dept->refresh();
        $this->assertEquals('Seksi Pengujian Mutu Terpadu', $dept->name);

        // 3. Create Position in this Department
        $response = $this->actingAs($user)->post(route('organization.positions.store'), [
            'code' => 'JAB-TEST',
            'title' => 'Analis Mutu Madya',
            'department_id' => $dept->id,
            'level' => 'Koordinator',
            'description' => 'Mengawasi standarisasi',
        ]);
        $response->assertRedirect();
        $pos = JobPosition::where('code', 'JAB-TEST')->first();
        $this->assertNotNull($pos);

        // 4. Update Position
        $response = $this->actingAs($user)->put(route('organization.positions.update', $pos), [
            'code' => 'JAB-TEST',
            'title' => 'Analis Mutu Utama',
            'department_id' => $dept->id,
            'level' => 'Pimpinan',
            'description' => 'Memimpin standarisasi',
        ]);
        $response->assertRedirect();
        $pos->refresh();
        $this->assertEquals('Analis Mutu Utama', $pos->title);

        // 5. Delete Position (has 0 employees)
        $response = $this->actingAs($user)->delete(route('organization.positions.destroy', $pos));
        $response->assertRedirect();
        $this->assertNull(JobPosition::where('code', 'JAB-TEST')->first());

        // 6. Delete Department (has 0 employees)
        $response = $this->actingAs($user)->delete(route('organization.departments.destroy', $dept));
        $response->assertRedirect();
        $this->assertNull(Department::where('code', 'UNIT-TEST')->first());
    }

    public function test_document_upload_and_download(): void
    {
        $user = User::where('role', 'admin_sdm')->first();
        $employee = Employee::first();

        $response = $this->actingAs($user)->post(route('documents.store'), [
            'employee_id' => $employee->id,
            'document_type' => 'Sertifikat',
            'title' => 'Sertifikat Manajemen Mutu ISO 9001',
            'document_number' => 'ISO/2026/001',
            'notes' => 'Sertifikasi terakreditasi internasional',
        ]);
        $response->assertRedirect();

        $doc = $employee->documents()->where('title', 'Sertifikat Manajemen Mutu ISO 9001')->first();
        $this->assertNotNull($doc);

        $downloadResponse = $this->actingAs($user)->get(route('documents.download', $doc));
        $downloadResponse->assertRedirect();
    }
}
