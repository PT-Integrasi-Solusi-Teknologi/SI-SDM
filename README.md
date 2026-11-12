# SI-SDM — Sistem Informasi Sumber Daya Manusia
### Balai Unit Pelaksana Teknis (UPT) Asrama Haji Terpadu — Ekosistem SI-AH 1447H

SI-SDM adalah modul pengelolaan sumber daya manusia dalam ekosistem **SI-AH Terpadu (Sistem Informasi Asrama Haji Terpadu)**. Sistem ini menjadi pusat data kepegawaian (*single source of truth*) yang mengelola seluruh siklus hidup pegawai, formasi jabatan, struktur organisasi, presensi operasional, permohonan cuti, matriks kompetensi, sertifikasi profesi, dan evaluasi kinerja pegawai.

---

## 🚀 Tech Stack

- **Backend**: Laravel 13 (PHP 8.4)
- **Database**: MySQL 8.0
- **Frontend**: Blade Templating + Tailwind CSS 4 + Alpine.js + Chart.js + Lucide Icons
- **Testing**: PHPUnit (100% Passed)
- **Code Standards**: Laravel Pint (PSR-12)

---

## 🌟 Fitur Utama

1. **Dashboard Eksekutif SDM**:
   - Statistik live pegawai (ASN, PPPK, Kontrak, Outsourcing).
   - Distribusi penempatan per unit kerja & status kehadiran harian.
   - Quick action persetujuan permohonan cuti & izin.
   - Leaderboard 5 pegawai berkinerja terbaik.
   - Real-time audit trail log aktivitas sistem.
2. **Direktori & Profil Detail Pegawai**:
   - Pencarian real-time, filter unit kerja, jenis kepegawaian, dan model kerja (WFO/Hybrid/Shift).
   - Profil terstruktur dengan 4 circular ring gauge kuota cuti.
   - Grafik akumulasi jam kerja (*hours logged*) dan tren evaluasi performa.
   - Riwayat mutasi, promosi, dan penempatan (*employee assignments*).
3. **Struktur Organisasi & Master Jabatan**:
   - Bagan hierarki interaktif visual unit kerja.
   - Master Unit Kerja (CRUD lengkap dengan modal interaktif).
   - Master Jabatan (CRUD lengkap dengan tingkat/level jabatan).
4. **Presensi & Jam Kerja Operasional**:
   - Rekap absensi harian, filter tanggal & status (Hadir, Terlambat, DL, Izin, Sakit).
   - Modal pencatatan presensi manual dan koreksi dispensasi.
5. **Manajemen Cuti & Izin**:
   - Pengajuan cuti terintegrasi dengan pemotongan sisa kuota otomatis.
   - Modal alur persetujuan (*approval*) dengan catatan atasan.
   - Modal penolakan (*rejection*) dengan pencatatan alasan resmi.
6. **Matriks Kompetensi & Sertifikasi**:
   - Standar kamus kompetensi kerja asrama haji.
   - Pemetaan level kemahiran pegawai (Dasar, Menengah, Mahir).
   - Manajemen sertifikasi profesi tervalidasi (BNSP, K3, LKPP).
7. **Penilaian Kinerja (IKP)**:
   - Evaluasi berkala dengan kalkulasi otomatis predikat kinerja (Sangat Baik, Baik, Cukup, Kurang).
   - Distribusi kategori capaian dan masukan evaluator.
8. **Repositori Dokumen Digital**:
   - Pengarsipan berkas SK Pengangkatan, Surat Tugas, Kontrak, KTP, dan Ijazah.
   - Simulasi unduh dokumen aman dengan jejak audit log.
9. **Laporan & Cetak Lembar Resmi**:
   - Ringkasan eksekutif dan lembar status kesiapan SDM ber-Kop Kementerian Agama RI lengkap dengan blok tanda tangan Kepala Balai.
10. **Sistem Peran & Akses Cepat (Demo Switcher)**:
    - 4 Role: `Super Admin`, `Admin SDM`, `Pimpinan (Kepala UPT)`, `Pegawai (Self Service)`.
    - Fitur 1-klik switcher peran pada Topbar dan Login.

---

## 📦 Panduan Instalasi Lokal

1. **Clone repositori**:
   ```bash
   git clone https://github.com/PT-Integrasi-Solusi-Teknologi/SI-SDM.git
   cd SI-SDM
   ```

2. **Pasang dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Pastikan pengaturan koneksi basis data MySQL di file `.env` sudah sesuai:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=si_sdm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Migrasi Database & Seeding Dummy Data (51 Pegawai)**:
   ```bash
   php artisan migrate --seed
   ```

5. **Build Aset Frontend**:
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka di peramban: `http://localhost:8000`

---

## 🔑 Akun Demo Pengujian

Semua akun menggunakan kata sandi bawaan: `password`

| Peran | Email | Keterangan |
|---|---|---|
| **Admin SDM** | `sdm@asramahaji.go.id` | Akses penuh operasional kepegawaian |
| **Kepala UPT (Pimpinan)** | `kepala@asramahaji.go.id` | Akses eksekutif, rekapitulasi & persetujuan |
| **Pegawai (Self Service)** | `siti.nurhaliza@asramahaji.go.id` | Akses mandiri pegawai (Mia Torres) |
| **Super Admin** | `admin@asramahaji.go.id` | Pengaturan sistem & manajemen pengguna |

---

## 🧪 Menjalankan Pengujian (Testing)

```bash
php artisan test
```

---

&copy; 2026 Balai Unit Pelaksana Teknis (UPT) Asrama Haji Terpadu &bull; Kementerian Agama Republik Indonesia
