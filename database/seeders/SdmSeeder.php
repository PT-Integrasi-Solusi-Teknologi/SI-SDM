<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\AuditLog;
use App\Models\Certification;
use App\Models\Competency;
use App\Models\CompetencyCategory;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\EmployeeCompetency;
use App\Models\EmployeeDocument;
use App\Models\EmploymentStatus;
use App\Models\EmploymentType;
use App\Models\JobPosition;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PerformancePeriod;
use App\Models\PerformanceReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SdmSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments / Unit Kerja (Hierarkis)
        $rootDept = Department::create([
            'code' => 'UPT-01',
            'name' => 'Balai UPT Asrama Haji',
            'parent_id' => null,
            'description' => 'Kantor Pimpinan UPT Asrama Haji Terpadu',
            'manager_name' => 'Drs. H. Ahmad Fauzi, M.M.',
            'color' => 'emerald',
            'sort_order' => 1,
        ]);

        $deptLayanan = Department::create([
            'code' => 'LAY-01',
            'name' => 'Seksi Pelayanan Jamaah & Akomodasi',
            'parent_id' => $rootDept->id,
            'description' => 'Pengelolaan wisma, penerimaan jamaah haji/umrah, konsumsi, dan sanitasi kamar',
            'manager_name' => 'Hj. Siti Rahmawati, S.E.',
            'color' => 'teal',
            'sort_order' => 2,
        ]);

        $deptSarpras = Department::create([
            'code' => 'SAR-01',
            'name' => 'Seksi Sarana & Prasarana',
            'parent_id' => $rootDept->id,
            'description' => 'Pemeliharaan gedung, sistem kelistrikan, genset, AC, plumbing, dan sound system',
            'manager_name' => 'Ir. Bambang Sudarmono, M.T.',
            'color' => 'cyan',
            'sort_order' => 3,
        ]);

        $deptKepegawaian = Department::create([
            'code' => 'SDM-01',
            'name' => 'Subbag Tata Usaha & Kepegawaian',
            'parent_id' => $rootDept->id,
            'description' => 'Administrasi SDM, persuratan, mutasi, diklat, dan hukum regulasi',
            'manager_name' => 'Dra. Endang Wulandari',
            'color' => 'green',
            'sort_order' => 4,
        ]);

        $deptKeuangan = Department::create([
            'code' => 'KEU-01',
            'name' => 'Subbag Keuangan & BMN',
            'parent_id' => $rootDept->id,
            'description' => 'Pengelolaan anggaran DIPA/PNBP, kas, perbendaharaan, dan BMN',
            'manager_name' => 'M. Rizky Pratama, S.Ak., M.Ak.',
            'color' => 'emerald',
            'sort_order' => 5,
        ]);

        $deptTI = Department::create([
            'code' => 'TI-01',
            'name' => 'Unit Teknologi Informasi & Data',
            'parent_id' => $rootDept->id,
            'description' => 'Infrastruktur jaringan, server, Command Center SI-AH, dan keamanan data',
            'manager_name' => 'Fajar Nugraha, S.Kom.',
            'color' => 'teal',
            'sort_order' => 6,
        ]);

        $deptKeamanan = Department::create([
            'code' => 'KMN-01',
            'name' => 'Unit Pengamanan & K3',
            'parent_id' => $rootDept->id,
            'description' => 'Ketertiban kawasan, pos satpam gerbang, CCTV, dan mitigasi kebakaran',
            'manager_name' => 'Kapten (Purn) Sutrisno Hadi',
            'color' => 'slate',
            'sort_order' => 7,
        ]);

        // 2. Job Positions / Master Jabatan
        $positionsData = [
            ['code' => 'JAB-01', 'title' => 'Kepala UPT Asrama Haji', 'department_id' => $rootDept->id, 'level' => 'Pimpinan', 'description' => 'Penanggung jawab utama operasional dan manajerial asrama haji'],
            ['code' => 'JAB-02', 'title' => 'Kasubbag Tata Usaha', 'department_id' => $deptKepegawaian->id, 'level' => 'Pimpinan', 'description' => 'Koordinator ketatausahaan, kepegawaian, dan kehumasan'],
            ['code' => 'JAB-03', 'title' => 'Kepala Seksi Pelayanan Jamaah', 'department_id' => $deptLayanan->id, 'level' => 'Koordinator', 'description' => 'Mengkoordinasi layanan akomodasi, katering, dan penerimaan kloter'],
            ['code' => 'JAB-04', 'title' => 'Kepala Seksi Sarpras', 'department_id' => $deptSarpras->id, 'level' => 'Koordinator', 'description' => 'Mengawasi utilitas gedung, mekanikal elektrikal, dan sarana umum'],
            ['code' => 'JAB-05', 'title' => 'HR Officer / Pengelola Kepegawaian', 'department_id' => $deptKepegawaian->id, 'level' => 'Staff', 'description' => 'Mengelola database kepegawaian, cuti, penilaian kinerja, dan arsip'],
            ['code' => 'JAB-06', 'title' => 'Koordinator Wisma & Kamar', 'department_id' => $deptLayanan->id, 'level' => 'Staff', 'description' => 'Pengawasan kesiapan kamar kloter haji dan tamu umum'],
            ['code' => 'JAB-07', 'title' => 'Petugas Front Office & Registrasi', 'department_id' => $deptLayanan->id, 'level' => 'Staff Pelaksana', 'description' => 'Check-in jamaah, validasi barcode gelang haji, dan kunci kamar'],
            ['code' => 'JAB-08', 'title' => 'Teknisi Listrik & Genset', 'department_id' => $deptSarpras->id, 'level' => 'Teknisi', 'description' => 'Pemeliharaan gardu listrik 500kVA, ATS genset, dan panel daya'],
            ['code' => 'JAB-09', 'title' => 'Teknisi Tata Udara & AC', 'department_id' => $deptSarpras->id, 'level' => 'Teknisi', 'description' => 'Perawatan rutin AC sentral aula dan AC split 450 kamar asrama'],
            ['code' => 'JAB-10', 'title' => 'Teknisi Plumbing & Pompa Air', 'department_id' => $deptSarpras->id, 'level' => 'Teknisi', 'description' => 'Pengawasan ground water tank, booster pump, dan instalasi air bersih'],
            ['code' => 'JAB-11', 'title' => 'Pranata Komputer / IT Specialist', 'department_id' => $deptTI->id, 'level' => 'Staff', 'description' => 'Pemeliharaan aplikasi SI-AH, access point WiFi jamaah, dan LAN'],
            ['code' => 'JAB-12', 'title' => 'Administrator Jaringan & CCTV', 'department_id' => $deptTI->id, 'level' => 'Staff Pelaksana', 'description' => 'Monitoring 120 titik CCTV dan server database SI-AH'],
            ['code' => 'JAB-13', 'title' => 'Bendahara Pengeluaran', 'department_id' => $deptKeuangan->id, 'level' => 'Staff', 'description' => 'Pencairan dana kegiatan, pelaporan SPJ, dan kas operasional'],
            ['code' => 'JAB-14', 'title' => 'Pengelola BMN & Pengadaan', 'department_id' => $deptKeuangan->id, 'level' => 'Staff', 'description' => 'Inventarisasi aset tetap, register BMN, dan stok gudang logistik'],
            ['code' => 'JAB-15', 'title' => 'Komandan Regu Satpam', 'department_id' => $deptKeamanan->id, 'level' => 'Koordinator', 'description' => 'Penjadwalan shift keamanan dan pengawalan rombongan bis jamaah'],
            ['code' => 'JAB-16', 'title' => 'Petugas Keamanan Kawasan', 'department_id' => $deptKeamanan->id, 'level' => 'Staff Pelaksana', 'description' => 'Patroli berkala pos gerbang masuk, parkir, dan wisma jamaah'],
            ['code' => 'JAB-17', 'title' => 'Pengawas Kebersihan & Sanitasi', 'department_id' => $deptLayanan->id, 'level' => 'Koordinator', 'description' => 'Pengendalian kualitas kebersihan asrama, disinfeksi kamar, dan sampah'],
            ['code' => 'JAB-18', 'title' => 'Housekeeping & Petugas Kamar', 'department_id' => $deptLayanan->id, 'level' => 'Pelaksana', 'description' => 'Penyiapan linen, pembersihan sprei kamar asrama, dan amenities'],
        ];

        $positions = [];
        foreach ($positionsData as $p) {
            $positions[$p['code']] = JobPosition::create($p);
        }

        // 3. Employment Types
        $empTypes = [
            'ASN' => EmploymentType::create(['code' => 'ASN', 'name' => 'Pegawai Negeri Sipil (PNS)']),
            'PPPK' => EmploymentType::create(['code' => 'PPPK', 'name' => 'Pegawai Pemerintah dg Perjanjian Kerja (PPPK)']),
            'KONTRAK' => EmploymentType::create(['code' => 'KONTRAK', 'name' => 'Pegawai Kontrak / BLU']),
            'OUTSOURCING' => EmploymentType::create(['code' => 'OUTSOURCING', 'name' => 'Tenaga Alih Daya']),
        ];

        // 4. Employment Statuses
        $empStatuses = [
            'AKTIF' => EmploymentStatus::create(['code' => 'AKTIF', 'name' => 'Aktif Bekerja', 'badge_color' => 'emerald']),
            'CUTI' => EmploymentStatus::create(['code' => 'CUTI', 'name' => 'Sedang Cuti', 'badge_color' => 'amber']),
            'TUGAS_BELAJAR' => EmploymentStatus::create(['code' => 'TUGAS_BELAJAR', 'name' => 'Tugas Belajar', 'badge_color' => 'blue']),
            'NONAKTIF' => EmploymentStatus::create(['code' => 'NONAKTIF', 'name' => 'Non-Aktif / Pensiun', 'badge_color' => 'rose']),
        ];

        // 5. Competency Categories & Competencies
        $catTeknis = CompetencyCategory::create(['name' => 'Kompetensi Teknis Asrama & Sarpras', 'description' => 'Pemeliharaan fasilitas, mekanikal, elektrikal, dan utilitas']);
        $catPelayanan = CompetencyCategory::create(['name' => 'Layanan Jamaah & Hospitality', 'description' => 'Pelayanan prima jamaah haji/umrah, handling lansia, dan reservasi']);
        $catIT = CompetencyCategory::create(['name' => 'Teknologi Informasi & Digital', 'description' => 'Pengoperasian sistem informasi SI-AH, jaringan, dan keamanan siber']);
        $catManajerial = CompetencyCategory::create(['name' => 'Manajerial & Regulasi Kepegawaian', 'description' => 'Kepemimpinan, pengawasan anggaran, dan manajemen SDM']);
        $catK3 = CompetencyCategory::create(['name' => 'K3 & Tanggap Darurat Bencana', 'description' => 'Penanganan kebakaran, P3K, dan evakuasi gempa/bencana']);

        $competenciesList = [
            ['code' => 'KOMP-01', 'category_id' => $catPelayanan->id, 'name' => 'Standar Pelayanan Hospitality Jamaah Haji', 'target_level' => 'Mahir'],
            ['code' => 'KOMP-02', 'category_id' => $catPelayanan->id, 'name' => 'Pendampingan & Handling Jamaah Lansia & Disabilitas', 'target_level' => 'Menengah'],
            ['code' => 'KOMP-03', 'category_id' => $catTeknis->id, 'name' => 'Pemeliharaan Gardu Trafo & ATS Genset Otomatis', 'target_level' => 'Mahir'],
            ['code' => 'KOMP-04', 'category_id' => $catTeknis->id, 'name' => 'Perawatan AC Central Chiller & Split Inverter', 'target_level' => 'Menengah'],
            ['code' => 'KOMP-05', 'category_id' => $catIT->id, 'name' => 'Administrasi Sistem SI-AH Terpadu & Database Cloud', 'target_level' => 'Mahir'],
            ['code' => 'KOMP-06', 'category_id' => $catIT->id, 'name' => 'Manajemen Jaringan LAN & Hotspot Jamaah 10.000 User', 'target_level' => 'Menengah'],
            ['code' => 'KOMP-07', 'category_id' => $catManajerial->id, 'name' => 'Pengelolaan Kepegawaian & Manajemen Kinerja SDM', 'target_level' => 'Mahir'],
            ['code' => 'KOMP-08', 'category_id' => $catManajerial->id, 'name' => 'Pengadaan Barang & Jasa Pemerintah (PBJP Sertifikasi)', 'target_level' => 'Menengah'],
            ['code' => 'KOMP-09', 'category_id' => $catK3->id, 'name' => 'Penanganan Pertama Pada Kecelakaan (P3K)', 'target_level' => 'Menengah'],
            ['code' => 'KOMP-10', 'category_id' => $catK3->id, 'name' => 'Operasional Sistem Proteksi Kebakaran Gedung & Hydrant', 'target_level' => 'Mahir'],
        ];

        $createdComps = [];
        foreach ($competenciesList as $c) {
            $createdComps[] = Competency::create($c);
        }

        // 6. Leave Types
        $leaveTypes = [
            'TAHUNAN' => LeaveType::create(['name' => 'Cuti Tahunan', 'code' => 'TAHUNAN', 'default_quota' => 12, 'color' => 'emerald', 'description' => 'Hak cuti tahunan pegawai aktif']),
            'SAKIT' => LeaveType::create(['name' => 'Cuti Sakit', 'code' => 'SAKIT', 'default_quota' => 14, 'color' => 'amber', 'description' => 'Cuti dengan surat keterangan dokter']),
            'PENTING' => LeaveType::create(['name' => 'Cuti Alasan Penting', 'code' => 'PENTING', 'default_quota' => 10, 'color' => 'blue', 'description' => 'Pernikahan, anggota keluarga meninggal, atau bencana']),
            'MELAHIRKAN' => LeaveType::create(['name' => 'Cuti Melahirkan', 'code' => 'MELAHIRKAN', 'default_quota' => 90, 'color' => 'purple', 'description' => 'Cuti persalinan bagi pegawai wanita']),
            'IBADAH' => LeaveType::create(['name' => 'Cuti Ibadah Haji / Umrah', 'code' => 'IBADAH', 'default_quota' => 45, 'color' => 'teal', 'description' => 'Penunaian rukun ibadah haji ke tanah suci']),
        ];

        // 7. Performance Period
        $period2026 = PerformancePeriod::create([
            'name' => 'Penilaian Kinerja Periode 2026',
            'year' => 2026,
            'period_type' => 'Tahunan',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
        ]);

        $period2025 = PerformancePeriod::create([
            'name' => 'Penilaian Kinerja Periode 2025',
            'year' => 2025,
            'period_type' => 'Tahunan',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => false,
        ]);

        // 8. 50 Realistic Employees (Bertema Asrama Haji & Layanan Publik)
        $rawEmployees = [
            // Featured Employee 1 (Mirip Desain Referensi "Mia Torres / Siti Nurhaliza - CRV-2354")
            [
                'code' => 'CRV-2354',
                'name' => 'Mia Torres / Siti Nurhaliza',
                'nik' => '3275015803900004',
                'nip' => '199003262015022003',
                'gender' => 'Perempuan',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '1990-03-26',
                'email' => 'mia.torres@asramahaji.go.id',
                'phone' => '+62 851-3456-7890',
                'address' => 'Jl. Melati No. 45, Sleman, Yogyakarta, Indonesia',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
                'type' => 'ASN',
                'status' => 'AKTIF',
                'dept' => $deptKepegawaian->id,
                'pos' => 'JAB-05',
                'work_model' => 'Hybrid',
                'join_date' => '2022-02-09',
                'salary' => 8500000,
                'notes' => 'Pegawai teladan Q2. Memiliki etos kerja tinggi dalam digitalisasi data kepegawaian dan kepemimpinan dalam pelatihan internal.',
            ],
            // Pimpinan
            [
                'code' => 'AH-2024-001',
                'name' => 'Drs. H. Ahmad Fauzi, M.M.',
                'nik' => '3171011505700001',
                'nip' => '197005151996031001',
                'gender' => 'Laki-laki',
                'birth_place' => 'Surabaya',
                'birth_date' => '1970-05-15',
                'email' => 'ahmad.fauzi@asramahaji.go.id',
                'phone' => '+62 811-2233-4455',
                'address' => 'Jl. Cempaka Putih Tengah No. 12, Jakarta Pusat',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
                'type' => 'ASN',
                'status' => 'AKTIF',
                'dept' => $rootDept->id,
                'pos' => 'JAB-01',
                'work_model' => 'WFO',
                'join_date' => '2016-01-10',
                'salary' => 16500000,
                'notes' => 'Kepala UPT Asrama Haji Terpadu. Mengarahkan integrasi ekosistem SI-AH.',
            ],
            [
                'code' => 'AH-2024-002',
                'name' => 'Dra. Endang Wulandari',
                'nik' => '3172024808740002',
                'nip' => '197408182000032002',
                'gender' => 'Perempuan',
                'birth_place' => 'Solo',
                'birth_date' => '1974-08-18',
                'email' => 'endang.w@asramahaji.go.id',
                'phone' => '+62 812-3456-7891',
                'address' => 'Jl. Tebet Timur Dalam VI No. 8, Jakarta Selatan',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&auto=format&fit=crop&q=80',
                'type' => 'ASN',
                'status' => 'AKTIF',
                'dept' => $deptKepegawaian->id,
                'pos' => 'JAB-02',
                'work_model' => 'WFO',
                'join_date' => '2018-03-01',
                'salary' => 12500000,
                'notes' => 'Kasubbag Tata Usaha, pembina kepegawaian dan kearsipan persuratan.',
            ],
            [
                'code' => 'AH-2024-003',
                'name' => 'Hj. Siti Rahmawati, S.E.',
                'nik' => '3275015809790003',
                'nip' => '197909182006042004',
                'gender' => 'Perempuan',
                'birth_place' => 'Bandung',
                'birth_date' => '1979-09-18',
                'email' => 'siti.rahma@asramahaji.go.id',
                'phone' => '+62 813-9876-5432',
                'address' => 'Komplek Harapan Indah Blok DB No. 5, Bekasi',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
                'type' => 'ASN',
                'status' => 'AKTIF',
                'dept' => $deptLayanan->id,
                'pos' => 'JAB-03',
                'work_model' => 'WFO',
                'join_date' => '2019-07-15',
                'salary' => 11000000,
                'notes' => 'Kasi Pelayanan Jamaah. Penanggung jawab alur kedatangan dan pelepasan 80 kloter.',
            ],
            [
                'code' => 'AH-2024-004',
                'name' => 'Ir. Bambang Sudarmono, M.T.',
                'nik' => '3174011211750005',
                'nip' => '197511122002121001',
                'gender' => 'Laki-laki',
                'birth_place' => 'Semarang',
                'birth_date' => '1975-11-12',
                'email' => 'bambang.s@asramahaji.go.id',
                'phone' => '+62 815-1122-3344',
                'address' => 'Jl. Pejaten Barat No. 20, Pasar Minggu, Jakarta Selatan',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&auto=format&fit=crop&q=80',
                'type' => 'ASN',
                'status' => 'AKTIF',
                'dept' => $deptSarpras->id,
                'pos' => 'JAB-04',
                'work_model' => 'WFO',
                'join_date' => '2017-04-10',
                'salary' => 11500000,
                'notes' => 'Kasi Sarpras. Bertanggung jawab atas kelaikan operasional seluruh gedung dan utilitas.',
            ],
            [
                'code' => 'AH-2024-005',
                'name' => 'Fajar Nugraha, S.Kom.',
                'nik' => '3276012404880006',
                'nip' => '198804242014031002',
                'gender' => 'Laki-laki',
                'birth_place' => 'Bogor',
                'birth_date' => '1988-04-24',
                'email' => 'fajar.nugraha@asramahaji.go.id',
                'phone' => '+62 818-0808-9999',
                'address' => 'Jl. Margonda Raya No. 101, Depok',
                'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400&auto=format&fit=crop&q=80',
                'type' => 'ASN',
                'status' => 'AKTIF',
                'dept' => $deptTI->id,
                'pos' => 'JAB-11',
                'work_model' => 'Hybrid',
                'join_date' => '2020-01-05',
                'salary' => 9500000,
                'notes' => 'Kepala Unit TI. Mengawal implementasi Command Center SI-AH.',
            ],
            // 45 Pegawai Lainnya
            [
                'code' => 'AH-2024-006', 'name' => 'M. Rizky Pratama, S.Ak., M.Ak.', 'nik' => '3175011902890001', 'nip' => '198902192015021001',
                'gender' => 'Laki-laki', 'birth_place' => 'Palembang', 'birth_date' => '1989-02-19', 'email' => 'rizky.pratama@asramahaji.go.id',
                'phone' => '+62 821-4567-8901', 'address' => 'Jl. Pondok Kelapa Indah No. 4, Jakarta Timur', 'avatar' => null,
                'type' => 'ASN', 'status' => 'AKTIF', 'dept' => $deptKeuangan->id, 'pos' => 'JAB-13', 'work_model' => 'WFO', 'join_date' => '2019-02-01', 'salary' => 9200000, 'notes' => 'Bendahara Pengeluaran teliti dan tepat waktu dalam rekonsiliasi.',
            ],
            [
                'code' => 'AH-2024-007', 'name' => 'Kapten (Purn) Sutrisno Hadi', 'nik' => '3275010101680002', 'nip' => null,
                'gender' => 'Laki-laki', 'birth_place' => 'Madiun', 'birth_date' => '1968-01-01', 'email' => 'sutrisno.hadi@asramahaji.go.id',
                'phone' => '+62 813-1234-5678', 'address' => 'Asrama Haji Blok Satpam No. 1, Pondok Gede', 'avatar' => null,
                'type' => 'KONTRAK', 'status' => 'AKTIF', 'dept' => $deptKeamanan->id, 'pos' => 'JAB-15', 'work_model' => 'Shift', 'join_date' => '2021-06-01', 'salary' => 7500000, 'notes' => 'Kepala Unit Pengamanan & K3 kawasan.',
            ],
            [
                'code' => 'AH-2024-008', 'name' => 'Dewi Anggraini, S.S.T.Ars', 'nik' => '3275014506920003', 'nip' => '199206052019022005',
                'gender' => 'Perempuan', 'birth_place' => 'Jakarta', 'birth_date' => '1992-06-05', 'email' => 'dewi.anggraini@asramahaji.go.id',
                'phone' => '+62 812-8899-7766', 'address' => 'Jl. Jatiwaringin Raya No. 88, Bekasi', 'avatar' => null,
                'type' => 'ASN', 'status' => 'AKTIF', 'dept' => $deptKepegawaian->id, 'pos' => 'JAB-05', 'work_model' => 'Hybrid', 'join_date' => '2021-08-15', 'salary' => 7800000, 'notes' => 'Spesialis arsip digital kepegawaian dan kepatuhan SK.',
            ],
            [
                'code' => 'AH-2024-009', 'name' => 'Agus Setiawan, S.T.', 'nik' => '3275011408870004', 'nip' => '198708142011011003',
                'gender' => 'Laki-laki', 'birth_place' => 'Cirebon', 'birth_date' => '1987-08-14', 'email' => 'agus.setiawan@asramahaji.go.id',
                'phone' => '+62 813-7766-5544', 'address' => 'Jl. Galaxy Raya No. 15, Bekasi Selatan', 'avatar' => null,
                'type' => 'ASN', 'status' => 'AKTIF', 'dept' => $deptSarpras->id, 'pos' => 'JAB-08', 'work_model' => 'WFO', 'join_date' => '2018-09-01', 'salary' => 8200000, 'notes' => 'Koordinator genset dan transmisi kelistrikan.',
            ],
            [
                'code' => 'AH-2024-010', 'name' => 'Hendra Kusuma, A.Md.T.', 'nik' => '3275012512930005', 'nip' => null,
                'gender' => 'Laki-laki', 'birth_place' => 'Tangerang', 'birth_date' => '1993-12-25', 'email' => 'hendra.k@asramahaji.go.id',
                'phone' => '+62 878-1122-4455', 'address' => 'Jl. Ciputat Raya No. 23, Tangerang Selatan', 'avatar' => null,
                'type' => 'PPPK', 'status' => 'AKTIF', 'dept' => $deptSarpras->id, 'pos' => 'JAB-09', 'work_model' => 'WFO', 'join_date' => '2022-04-01', 'salary' => 6500000, 'notes' => 'Teknisi AC split dan chiller berpengalaman.',
            ],
            [
                'code' => 'AH-2024-011', 'name' => 'Nurul Hidayah, S.Tr.Par.', 'nik' => '3171015609940006', 'nip' => '199409162020122002',
                'gender' => 'Perempuan', 'birth_place' => 'Malang', 'birth_date' => '1994-09-16', 'email' => 'nurul.hidayah@asramahaji.go.id',
                'phone' => '+62 812-9988-7711', 'address' => 'Jl. Rawamangun Muka No. 45, Jakarta Timur', 'avatar' => null,
                'type' => 'ASN', 'status' => 'AKTIF', 'dept' => $deptLayanan->id, 'pos' => 'JAB-06', 'work_model' => 'WFO', 'join_date' => '2021-01-15', 'salary' => 7400000, 'notes' => 'Koordinator Wisma Mina & Madinah, ramah dan cekatan.',
            ],
            [
                'code' => 'AH-2024-012', 'name' => 'Rian Ardiansyah, S.Kom.', 'nik' => '3275011805950007', 'nip' => null,
                'gender' => 'Laki-laki', 'birth_place' => 'Bandung', 'birth_date' => '1995-05-18', 'email' => 'rian.a@asramahaji.go.id',
                'phone' => '+62 856-7890-1234', 'address' => 'Jl. Kalimalang Baru No. 10, Bekasi', 'avatar' => null,
                'type' => 'PPPK', 'status' => 'AKTIF', 'dept' => $deptTI->id, 'pos' => 'JAB-12', 'work_model' => 'Hybrid', 'join_date' => '2023-03-01', 'salary' => 6800000, 'notes' => 'Administrator server dan integrasi API antar modul SI-AH.',
            ],
            [
                'code' => 'AH-2024-013', 'name' => 'Tri Wahyuni, S.E.', 'nik' => '3175014710910008', 'nip' => '199110072017052001',
                'gender' => 'Perempuan', 'birth_place' => 'Purwokerto', 'birth_date' => '1991-10-07', 'email' => 'tri.wahyuni@asramahaji.go.id',
                'phone' => '+62 813-2211-3344', 'address' => 'Jl. Buaran Indah No. 5, Duren Sawit, Jakarta Timur', 'avatar' => null,
                'type' => 'ASN', 'status' => 'AKTIF', 'dept' => $deptKeuangan->id, 'pos' => 'JAB-14', 'work_model' => 'WFO', 'join_date' => '2019-10-01', 'salary' => 7600000, 'notes' => 'Pengelola aset BMN dan inventarisasi sarana kamar asrama.',
            ],
            [
                'code' => 'AH-2024-014', 'name' => 'Budi Santoso', 'nik' => '3275011204850009', 'nip' => null,
                'gender' => 'Laki-laki', 'birth_place' => 'Kebumen', 'birth_date' => '1985-04-12', 'email' => 'budi.santoso@asramahaji.go.id',
                'phone' => '+62 877-3344-5566', 'address' => 'Jl. Raya Hankam No. 70, Pondok Gede, Bekasi', 'avatar' => null,
                'type' => 'KONTRAK', 'status' => 'AKTIF', 'dept' => $deptSarpras->id, 'pos' => 'JAB-10', 'work_model' => 'WFO', 'join_date' => '2020-05-10', 'salary' => 5800000, 'notes' => 'Teknisi plumbing dan pompa air berkinerja responsif.',
            ],
            [
                'code' => 'AH-2024-015', 'name' => 'Anisa Fitriani, A.Md.Par.', 'nik' => '3174015802960010', 'nip' => null,
                'gender' => 'Perempuan', 'birth_place' => 'Sukabumi', 'birth_date' => '1996-02-18', 'email' => 'anisa.fitriani@asramahaji.go.id',
                'phone' => '+62 819-0102-0304', 'address' => 'Jl. Kramat Jati No. 12, Jakarta Timur', 'avatar' => null,
                'type' => 'KONTRAK', 'status' => 'AKTIF', 'dept' => $deptLayanan->id, 'pos' => 'JAB-07', 'work_model' => 'Shift', 'join_date' => '2022-06-01', 'salary' => 5500000, 'notes' => 'Front office penerimaan kloter jamaah haji.',
            ],
            [
                'code' => 'AH-2024-016', 'name' => 'Eko Prasetyo', 'nik' => '3275011508890011', 'nip' => null,
                'gender' => 'Laki-laki', 'birth_place' => 'Klaten', 'birth_date' => '1989-08-15', 'email' => 'eko.prasetyo@asramahaji.go.id',
                'phone' => '+62 812-3344-5566', 'address' => 'Jl. Bambu Apus No. 22, Cipayung, Jakarta Timur', 'avatar' => null,
                'type' => 'OUTSOURCING', 'status' => 'AKTIF', 'dept' => $deptKeamanan->id, 'pos' => 'JAB-16', 'work_model' => 'Shift', 'join_date' => '2023-01-10', 'salary' => 5200000, 'notes' => 'Satpam gerbang utama dan pengatur lalu lintas bus haji.',
            ],
            [
                'code' => 'AH-2024-017', 'name' => 'Siti Aisyah, S.Sos.', 'nik' => '3175016207930012', 'nip' => '199307222019032004',
                'gender' => 'Perempuan', 'birth_place' => 'Medan', 'birth_date' => '1993-07-22', 'email' => 'siti.aisyah@asramahaji.go.id',
                'phone' => '+62 813-8877-6655', 'address' => 'Jl. Cipinang Muara No. 34, Jakarta Timur', 'avatar' => null,
                'type' => 'ASN', 'status' => 'CUTI', 'dept' => $deptLayanan->id, 'pos' => 'JAB-07', 'work_model' => 'WFO', 'join_date' => '2020-03-01', 'salary' => 7200000, 'notes' => 'Sedang mengambil cuti melahirkan 3 bulan.',
            ],
            [
                'code' => 'AH-2024-018', 'name' => 'Joko Widodo Susanto, S.Pd.', 'nik' => '3275011001880013', 'nip' => '198801102014021002',
                'gender' => 'Laki-laki', 'birth_place' => 'Boyolali', 'birth_date' => '1988-01-10', 'email' => 'joko.susanto@asramahaji.go.id',
                'phone' => '+62 812-4455-6677', 'address' => 'Jl. Raya Hankam Gang Swadaya No. 8, Bekasi', 'avatar' => null,
                'type' => 'ASN', 'status' => 'TUGAS_BELAJAR', 'dept' => $deptKepegawaian->id, 'pos' => 'JAB-05', 'work_model' => 'Remote', 'join_date' => '2016-09-01', 'salary' => 7800000, 'notes' => 'Sedang menempuh program Master Kebijakan Publik.',
            ],
            [
                'code' => 'AH-2024-019', 'name' => 'Lestari Handayani', 'nik' => '3275015011900014', 'nip' => null,
                'gender' => 'Perempuan', 'birth_place' => 'Magelang', 'birth_date' => '1990-11-10', 'email' => 'lestari.h@asramahaji.go.id',
                'phone' => '+62 878-9900-1122', 'address' => 'Jl. Lubang Buaya No. 17, Cipayung', 'avatar' => null,
                'type' => 'OUTSOURCING', 'status' => 'AKTIF', 'dept' => $deptLayanan->id, 'pos' => 'JAB-17', 'work_model' => 'Shift', 'join_date' => '2021-11-01', 'salary' => 5400000, 'notes' => 'Pengawas kebersihan wisma dan sanitasi aula.',
            ],
            [
                'code' => 'AH-2024-020', 'name' => 'Rahmat Hidayatullah', 'nik' => '3171011409920015', 'nip' => null,
                'gender' => 'Laki-laki', 'birth_place' => 'Banjarmasin', 'birth_date' => '1992-09-14', 'email' => 'rahmat.h@asramahaji.go.id',
                'phone' => '+62 857-1234-9876', 'address' => 'Jl. Percetakan Negara No. 50, Jakarta Pusat', 'avatar' => null,
                'type' => 'PPPK', 'status' => 'AKTIF', 'dept' => $deptSarpras->id, 'pos' => 'JAB-08', 'work_model' => 'WFO', 'join_date' => '2023-02-15', 'salary' => 6400000, 'notes' => 'Teknisi sound system aula utama dan penerangan luar.',
            ],
        ];

        // Generate tambahan nama hingga 50 data pegawai yang variatif dan lengkap
        $firstNamesM = ['Arif', 'Bayu', 'Candra', 'Deni', 'Faisal', 'Gilang', 'Hari', 'Ilham', 'Kurniawan', 'Lukman', 'Maulana', 'Nanang', 'Oki', 'Pandu', 'Rudi', 'Sigit', 'Taufik', 'Wahyu', 'Yoga', 'Zulham'];
        $firstNamesF = ['Citra', 'Dina', 'Erna', 'Fitri', 'Gita', 'Hani', 'Indah', 'Kartika', 'Lia', 'Maya', 'Nadia', 'Putri', 'Ratna', 'Salma', 'Tari', 'Vina', 'Wulan', 'Yulia', 'Zahra', 'Bella'];
        $lastNames = ['Pratama', 'Saputra', 'Kusuma', 'Lestari', 'Wijaya', 'Permana', 'Nugroho', 'Wibowo', 'Santoso', 'Utami', 'Siregar', 'Nasution', 'Hidayat', 'Mulyadi', 'Iskandar', 'Kusnadi', 'Setiawan', 'Firmansyah', 'Rahman', 'Suryadi'];

        $deptKeys = [$deptLayanan, $deptSarpras, $deptKepegawaian, $deptKeuangan, $deptTI, $deptKeamanan];
        $posKeys = array_keys($positions);

        for ($i = 21; $i <= 50; $i++) {
            $isFemale = ($i % 2 === 0);
            $fn = $isFemale ? $firstNamesF[($i * 3) % count($firstNamesF)] : $firstNamesM[($i * 5) % count($firstNamesM)];
            $ln = $lastNames[($i * 7) % count($lastNames)];
            $fullName = $fn.' '.$ln;
            $dept = $deptKeys[$i % count($deptKeys)];
            $pos = $posKeys[$i % count($posKeys)];
            $empType = ($i % 4 === 0) ? 'ASN' : (($i % 4 === 1) ? 'PPPK' : (($i % 4 === 2) ? 'KONTRAK' : 'OUTSOURCING'));
            $status = ($i === 28) ? 'CUTI' : (($i === 42) ? 'NONAKTIF' : 'AKTIF');

            $rawEmployees[] = [
                'code' => sprintf('AH-2024-%03d', $i),
                'name' => $fullName.($isFemale ? ', S.E.' : ', S.Kom.'),
                'nik' => sprintf('327501%02d%02d%02d00%02d', ($i % 28) + 1, ($i % 12) + 1, 85 + ($i % 15), $i),
                'nip' => ($empType === 'ASN') ? sprintf('19%02d%02d%02d201%01d0%01d%01d00%01d', 80 + ($i % 15), ($i % 12) + 1, ($i % 28) + 1, $i % 9, $i % 2, $isFemale ? 2 : 1, $i % 9) : null,
                'gender' => $isFemale ? 'Perempuan' : 'Laki-laki',
                'birth_place' => ($i % 3 === 0) ? 'Jakarta' : (($i % 3 === 1) ? 'Bandung' : 'Surabaya'),
                'birth_date' => Carbon::create(1985 + ($i % 15), ($i % 12) + 1, ($i % 28) + 1)->format('Y-m-d'),
                'email' => strtolower($fn.'.'.$ln).$i.'@asramahaji.go.id',
                'phone' => '+62 8'.rand(11, 99).'-'.rand(1000, 9999).'-'.rand(1000, 9999),
                'address' => 'Jl. Asrama Haji No. '.$i.', Pondok Gede, Jakarta Timur',
                'avatar' => null,
                'type' => $empType,
                'status' => $status,
                'dept' => $dept->id,
                'pos' => $pos,
                'work_model' => ($i % 3 === 0) ? 'WFO' : (($i % 3 === 1) ? 'Hybrid' : 'Shift'),
                'join_date' => Carbon::create(2018 + ($i % 6), ($i % 12) + 1, 10)->format('Y-m-d'),
                'salary' => 5500000 + ($i * 120000),
                'notes' => 'Pegawai aktif bidang '.$dept->name.' dengan dedikasi prima.',
            ];
        }

        // Insert Employees & Relasi
        $createdEmployees = [];
        foreach ($rawEmployees as $idx => $eData) {
            $emp = Employee::create([
                'employee_code' => $eData['code'],
                'name' => $eData['name'],
                'nik' => $eData['nik'],
                'nip' => $eData['nip'],
                'gender' => $eData['gender'],
                'birth_place' => $eData['birth_place'],
                'birth_date' => $eData['birth_date'],
                'email' => $eData['email'],
                'phone' => $eData['phone'],
                'address' => $eData['address'],
                'avatar' => $eData['avatar'],
                'employment_type_id' => $empTypes[$eData['type']]->id,
                'employment_status_id' => $empStatuses[$eData['status']]->id,
                'department_id' => $eData['dept'],
                'job_position_id' => $positions[$eData['pos']]->id,
                'work_model' => $eData['work_model'],
                'join_date' => $eData['join_date'],
                'contract_end_date' => ($eData['type'] === 'KONTRAK' || $eData['type'] === 'PPPK') ? '2026-12-31' : null,
                'notes' => $eData['notes'],
                'base_salary' => $eData['salary'],
                'is_active' => ($eData['status'] !== 'NONAKTIF'),
            ]);

            $createdEmployees[] = $emp;

            // Buat Riwayat Penempatan Awal
            EmployeeAssignment::create([
                'employee_id' => $emp->id,
                'department_id' => $emp->department_id,
                'job_position_id' => $emp->job_position_id,
                'assignment_type' => 'Penempatan Awal',
                'start_date' => $emp->join_date,
                'sk_number' => 'SK/UPT-AH/'.date('Y', strtotime($emp->join_date)).'/'.sprintf('%03d', $emp->id),
                'sk_date' => $emp->join_date,
                'notes' => 'Surat Keputusan pengangkatan dan penempatan awal di '.$emp->department->name,
                'created_by' => 'Admin Kepegawaian',
            ]);

            // Buat Saldo Cuti 2026
            foreach ($leaveTypes as $code => $lt) {
                $used = ($code === 'TAHUNAN') ? rand(0, 4) : (($code === 'SAKIT') ? rand(0, 2) : 0);
                LeaveBalance::create([
                    'employee_id' => $emp->id,
                    'leave_type_id' => $lt->id,
                    'year' => 2026,
                    'allocated_days' => $lt->default_quota,
                    'used_days' => $used,
                    'remaining_days' => max(0, $lt->default_quota - $used),
                ]);
            }

            // Assign 2-3 Kompetensi per Pegawai
            $assignedComps = array_rand($createdComps, 3);
            $levels = ['Dasar', 'Menengah', 'Mahir'];
            foreach ((array) $assignedComps as $compIdx) {
                EmployeeCompetency::create([
                    'employee_id' => $emp->id,
                    'competency_id' => $createdComps[$compIdx]->id,
                    'level' => $levels[rand(0, 2)],
                    'assessed_date' => '2025-11-15',
                    'assessor_name' => 'Tim Penguji BKN & Kemenag',
                    'notes' => 'Telah teruji dalam asesmen kompetensi tahunan',
                ]);
            }

            // Tambahkan Sertifikasi untuk sebagian pegawai
            if ($idx % 2 === 0) {
                Certification::create([
                    'employee_id' => $emp->id,
                    'name' => ($idx % 4 === 0) ? 'Sertifikasi BNSP Hospitality & Pelayanan Prima' : (($idx % 4 === 1) ? 'Sertifikasi Ahli K3 Umum Kemenaker' : 'Sertifikasi Kompetensi PBJP Tingkat Dasar'),
                    'certificate_number' => 'CERT-BNSP-'.(2023 + ($idx % 3)).'-'.sprintf('%04d', $emp->id),
                    'issuer' => ($idx % 4 === 0) ? 'BNSP Indonesia' : 'Kementerian Ketenagakerjaan RI',
                    'issue_date' => '2023-05-10',
                    'expiry_date' => '2027-05-10',
                    'status' => 'active',
                ]);
            }

            // Dokumen Default
            EmployeeDocument::create([
                'employee_id' => $emp->id,
                'document_type' => 'SK Pengangkatan',
                'title' => 'SK Pengangkatan Pegawai - '.$emp->name,
                'document_number' => 'SK/UPT-AH/'.sprintf('%03d', $emp->id).'/2024',
                'file_path' => 'documents/sample_sk.pdf',
                'file_size' => '1.4 MB',
                'file_type' => 'pdf',
                'notes' => 'Dokumen sah penetapan kepegawaian',
            ]);

            EmployeeDocument::create([
                'employee_id' => $emp->id,
                'document_type' => 'Kontrak Kerja',
                'title' => 'Perjanjian Kontrak Kerja & Pakta Integritas',
                'document_number' => 'PK-AH/'.sprintf('%03d', $emp->id),
                'file_path' => 'documents/sample_contract.pdf',
                'file_size' => '850 KB',
                'file_type' => 'pdf',
                'notes' => 'Pakta integritas pelayanan ramah lansia',
            ]);

            // Review Kinerja
            PerformanceReview::create([
                'employee_id' => $emp->id,
                'period_id' => $period2025->id,
                'score' => rand(82, 96) + (rand(0, 9) / 10),
                'rating' => ($idx % 5 === 0) ? 'Sangat Baik' : 'Baik',
                'evaluator_name' => 'Drs. H. Ahmad Fauzi, M.M.',
                'achievements' => 'Menyelesaikan operasional kepulangan kloter dengan kepuasan jamaah 94%',
                'feedback' => 'Pertahankan komunikasi antar tim dan pemanfaatan sistem SI-AH terpadu',
                'review_date' => '2025-12-20',
            ]);

            // Catatan Kehadiran 7 Hari Terakhir
            for ($d = 6; $d >= 0; $d--) {
                $targetDate = Carbon::today()->subDays($d);
                if ($targetDate->isWeekend()) {
                    continue;
                }
                $statuses = ['hadir', 'hadir', 'hadir', 'terlambat', 'dinas_luar'];
                $st = $statuses[($emp->id + $d) % count($statuses)];
                $cin = ($st === 'terlambat') ? '08:24:00' : '07:45:00';
                $cout = '16:30:00';
                $hours = ($st === 'terlambat') ? 7.6 : 8.25;

                AttendanceRecord::create([
                    'employee_id' => $emp->id,
                    'date' => $targetDate->format('Y-m-d'),
                    'clock_in' => $cin,
                    'clock_out' => $cout,
                    'work_hours' => $hours,
                    'status' => $st,
                    'location' => 'Kantor UPT Asrama Haji Terpadu',
                    'notes' => ($st === 'terlambat') ? 'Keterlambatan macet jalan tol pondok gede' : null,
                ]);
            }
        }

        // 9. Leave Requests (Campuran Pending, Approved, Rejected)
        $leaveSamples = [
            ['emp' => $createdEmployees[0]->id, 'type' => $leaveTypes['TAHUNAN']->id, 'days' => 3, 'status' => 'pending', 'reason' => 'Keperluan keluarga dan mendampingi orang tua di Solo'],
            ['emp' => $createdEmployees[10]->id, 'type' => $leaveTypes['SAKIT']->id, 'days' => 2, 'status' => 'approved', 'reason' => 'Demam dan radang tenggorokan (Surat Dokter Terlampir)'],
            ['emp' => $createdEmployees[14]->id, 'type' => $leaveTypes['PENTING']->id, 'days' => 2, 'status' => 'pending', 'reason' => 'Menghadiri pernikahan adik kandung di Bandung'],
            ['emp' => $createdEmployees[16]->id, 'type' => $leaveTypes['MELAHIRKAN']->id, 'days' => 90, 'status' => 'approved', 'reason' => 'Cuti bersalin anak kedua sesuai anjuran bidan/dokter RS'],
            ['emp' => $createdEmployees[8]->id, 'type' => $leaveTypes['TAHUNAN']->id, 'days' => 4, 'status' => 'approved', 'reason' => 'Cuti tahunan untuk renovasi rumah tinggal'],
            ['emp' => $createdEmployees[11]->id, 'type' => $leaveTypes['TAHUNAN']->id, 'days' => 5, 'status' => 'rejected', 'reason' => 'Cuti liburan bersamaan dengan kedatangan gelombang 1 kloter haji'],
        ];

        foreach ($leaveSamples as $idx => $ls) {
            $start = Carbon::today()->addDays($idx + 1);
            $end = (clone $start)->addDays($ls['days'] - 1);
            LeaveRequest::create([
                'ticket_number' => sprintf('LV-2026-%04d', $idx + 1),
                'employee_id' => $ls['emp'],
                'leave_type_id' => $ls['type'],
                'start_date' => $start->format('Y-m-d'),
                'end_date' => $end->format('Y-m-d'),
                'duration_days' => $ls['days'],
                'reason' => $ls['reason'],
                'status' => $ls['status'],
                'approver_name' => ($ls['status'] !== 'pending') ? 'Dra. Endang Wulandari' : null,
                'action_at' => ($ls['status'] !== 'pending') ? now() : null,
                'approver_notes' => ($ls['status'] === 'rejected') ? 'Mohon dijadwalkan ulang setelah masa operasional puncak haji selesai.' : (($ls['status'] === 'approved') ? 'Disetujui. Pastikan serah terima tugas piket berjalan lancar.' : null),
            ]);
        }

        // 10. Default System Users
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@asramahaji.go.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'employee_id' => null,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Kepegawaian (SDM)',
            'email' => 'sdm@asramahaji.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin_sdm',
            'employee_id' => $createdEmployees[0]->id, // Mia Torres / Siti Nurhaliza
            'avatar' => $createdEmployees[0]->avatar,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Drs. H. Ahmad Fauzi (Kepala UPT)',
            'email' => 'pimpinan@asramahaji.go.id',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'employee_id' => $createdEmployees[1]->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Pegawai Sample (Agus Setiawan)',
            'email' => 'pegawai@asramahaji.go.id',
            'password' => Hash::make('password'),
            'role' => 'pegawai',
            'employee_id' => $createdEmployees[8]->id,
            'is_active' => true,
        ]);

        // 11. Initial Audit Logs
        AuditLog::create([
            'user_name' => 'System Initializer',
            'action' => 'CREATE',
            'module' => 'System',
            'description' => 'Inisialisasi master data unit kerja, jabatan, dan 50 pegawai SI-SDM Asrama Haji.',
            'ip_address' => '127.0.0.1',
        ]);
        AuditLog::create([
            'user_name' => 'Dra. Endang Wulandari',
            'action' => 'APPROVE',
            'module' => 'Cuti & Izin',
            'description' => 'Menyetujui pengajuan Cuti Sakit LV-2026-0002 an. Hendra Kusuma.',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
