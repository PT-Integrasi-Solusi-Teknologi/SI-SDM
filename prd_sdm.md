# PRODUCT REQUIREMENTS DOCUMENT (PRD)

# SI-SDM — Sistem Informasi Sumber Daya Manusia

| Informasi | Detail |
|---|---|
| Produk | SI-AH TERPADU — Modul SI-SDM |
| Dokumen | Product Requirements Document |
| Versi | 1.0 — Draft untuk implementasi/demo |
| Tanggal | 10 November 2026 |
| Status | Draft; beberapa aturan bisnis menunggu konfirmasi client |
| Referensi utama | HRIS PlatKa Demo dan Odoo Employees / HR |

---

## 1. Ringkasan Produk

SI-SDM adalah modul pengelolaan sumber daya manusia dalam ekosistem **SI-AH TERPADU (Sistem Informasi Asrama Haji Terpadu)**. Modul ini menjadi pusat data kepegawaian dan kompetensi pegawai yang dapat digunakan oleh modul lain sesuai hak akses dan kebutuhan operasional.

SI-SDM bukan hanya aplikasi CRUD pegawai. Modul harus membantu pengelola mengetahui:

- Siapa saja pegawai yang terdaftar dan bagaimana status kepegawaiannya.
- Pegawai berada di unit kerja dan jabatan apa.
- Riwayat penempatan, penugasan, dan perubahan jabatan.
- Kehadiran, cuti, serta izin pegawai.
- Kompetensi dan sertifikasi yang dimiliki pegawai.
- Dokumen dan riwayat kepegawaian.
- Informasi yang dibutuhkan pimpinan untuk memantau kondisi SDM.

**Prinsip integrasi utama:** SI-SDM menjadi sumber utama (*source of truth*) untuk data induk pegawai, struktur organisasi, jabatan, penempatan, dan kompetensi pegawai. Modul lain menggunakan data yang disediakan SI-SDM melalui mekanisme integrasi resmi. Modul lain tidak membuat salinan master pegawai yang dikelola secara terpisah.

## 2. Latar Belakang dan Konteks

SI-AH TERPADU terdiri dari Command Center dan tujuh modul:

1. SI-LAYAN — Layanan dan jadwal.
2. SI-ASET — Aset dan pemeliharaan.
3. SI-PBJP — Pengadaan dan persediaan.
4. SI-KEU — Monitoring anggaran.
5. SI-DIKLAT — Pelatihan dan pembelajaran.
6. SI-SDM — Sumber daya manusia.
7. SI-GREEN — Kinerja lingkungan.

Dalam rancangan ini, SI-SDM berinteraksi dengan enam modul lainnya. Contohnya, SI-LAYAN menggunakan informasi pegawai untuk penugasan petugas layanan, sedangkan SI-DIKLAT mengirimkan hasil pelatihan yang sudah diverifikasi agar kompetensi pegawai dapat diperbarui.

Dokumen ini berfokus pada **SI-SDM saja**. Modul lain hanya dijelaskan sejauh diperlukan untuk mendefinisikan kebutuhan integrasi.

## 3. Referensi Produk dan Batas Penggunaan Referensi

### 3.1 HRIS PlatKa

HRIS PlatKa digunakan sebagai referensi utama untuk pola HRIS operasional, terutama:

- Dashboard ringkasan SDM, statistik, aktivitas terbaru, dan approval tertunda.
- Data pegawai dan riwayat kepegawaian.
- Struktur organisasi, cabang/unit, departemen, dan jabatan.
- Absensi, koreksi absensi, dan rekap kehadiran.
- Cuti, persetujuan, dan saldo cuti.
- Dokumen HR dan arsip pegawai.
- Laporan manajemen.
- Kontrol akses dan audit trail.

Halaman demo desain PlatKa menyatakan bahwa demo tersebut merupakan desain untuk keperluan demonstrasi, bukan aplikasi fungsional penuh. Karena itu, gunakan sebagai **referensi tampilan dan pola navigasi**, bukan sebagai bukti bahwa setiap interaksi backend tersedia atau harus ditiru persis.

### 3.2 Odoo Employees / HR

Odoo digunakan sebagai referensi untuk kedalaman profil pegawai dan keterkaitan data antarfitur, khususnya:

- Profil pegawai dengan informasi pekerjaan dan informasi pribadi yang dibatasi.
- Resume, pendidikan, pengalaman, skills, dan sertifikasi.
- Informasi kerja, atasan, approver, jadwal kerja, dan struktur organisasi.
- Organization chart.
- Kontrak dan dokumen pegawai.
- Integrasi kehadiran, cuti, appraisal, onboarding/offboarding, dan pelatihan.
- Hak akses berdasarkan peran.

Odoo memisahkan beberapa proses HR ke aplikasi terkait. SI-SDM juga perlu mempertahankan batas kepemilikan data: misalnya, materi dan penyelenggaraan pelatihan dikelola SI-DIKLAT, sedangkan profil kompetensi pegawai ditampilkan dan dikelola di SI-SDM.

**Prinsip adaptasi:** jangan menyalin seluruh fitur PlatKa atau Odoo secara otomatis. Prioritaskan fitur yang relevan untuk operasional Asrama Haji dan tandai hal yang belum dipastikan client sebagai TBD.

## 4. Tujuan Produk

### 4.1 Tujuan Utama

1. Memusatkan data induk pegawai dalam satu modul.
2. Menyajikan profil pegawai secara terstruktur, termasuk informasi pekerjaan, kompetensi, dokumen, dan riwayat.
3. Memudahkan pengelolaan struktur organisasi, jabatan, dan penempatan.
4. Mendukung proses administrasi kehadiran, cuti, dan izin sesuai kebijakan yang nantinya ditetapkan client.
5. Memberikan informasi SDM yang relevan kepada admin dan pimpinan.
6. Menyediakan data pegawai yang konsisten untuk kebutuhan integrasi SI-AH TERPADU.
7. Menjaga kerahasiaan data pribadi dan menyediakan jejak audit untuk aktivitas penting.

### 4.2 Indikator Keberhasilan

- Admin dapat mencari dan membuka profil pegawai tanpa mengelola data yang sama pada banyak tempat.
- Perubahan jabatan atau unit kerja tercatat sebagai riwayat, bukan menimpa data historis.
- Pimpinan dapat melihat ringkasan SDM sesuai lingkup kewenangannya.
- Pengajuan cuti/izin dapat ditelusuri dari pengajuan hingga keputusan.
- Data kompetensi dapat ditelusuri ke bukti atau sumber verifikasinya.
- Modul lain dapat mengambil data pegawai yang diperlukan melalui API/kontrak integrasi yang disepakati.
- Akses terhadap informasi sensitif dibatasi berdasarkan role dan lingkup organisasi.

## 5. Batasan Ruang Lingkup

### 5.1 Ruang Lingkup Utama (Target Demo SI-SDM)

- Dashboard SDM.
- Manajemen data dan profil pegawai.
- Struktur organisasi, unit kerja, dan jabatan.
- Penempatan dan riwayat jabatan/penugasan.
- Kompetensi, skill, dan sertifikasi pegawai.
- Dokumen kepegawaian.
- Direktori pegawai dan organization chart.
- Role-based access control (RBAC) dan audit aktivitas dasar.
- Data contoh/demo yang konsisten untuk memperlihatkan alur fitur.

### 5.2 Ruang Lingkup Lanjutan

- Absensi dan rekap kehadiran.
- Pengajuan cuti dan izin beserta approval.
- Penilaian kinerja.
- Onboarding/offboarding.
- Notifikasi dan pengingat.
- Laporan SDM lanjutan.
- Integrasi dengan perangkat absensi jika metode dan perangkat telah dikonfirmasi.
- Integrasi API dengan enam modul lain.

### 5.3 Di Luar Ruang Lingkup Awal / Perlu Persetujuan

Fitur-fitur berikut tampak pada sebagian referensi HRIS, tetapi **jangan dibangun sebagai kewajiban MVP tanpa persetujuan client**:

- Payroll, komponen gaji, tunjangan, potongan, dan slip gaji.
- Kasbon atau pinjaman pegawai.
- Rekrutmen dan Applicant Tracking System.
- Pengelolaan pajak, BPJS, atau kepatuhan penggajian.
- Integrasi resmi dengan sistem kepegawaian pemerintah/e-Kinerja/SKP.
- Tanda tangan elektronik berbayar atau integrasi pihak ketiga tertentu.

Alasannya: kewenangan pengelolaan payroll, kasbon, rekrutmen, dan penilaian resmi untuk pegawai di lingkungan Asrama Haji belum dikonfirmasi. Jangan membuat asumsi bahwa seluruh fitur HRIS perusahaan swasta berlaku di lingkungan ini.

## 6. Pengguna dan Hak Akses

Sistem minimal mendukung role berikut. Nama role dapat disesuaikan dengan struktur organisasi yang disahkan client.

| Role | Tanggung jawab | Akses utama |
|---|---|---|
| Super Admin | Konfigurasi sistem dan akun | Seluruh konfigurasi; tidak otomatis berarti dapat membaca semua dokumen pribadi tanpa kebutuhan |
| Admin SDM | Operasional data kepegawaian | Data pegawai, struktur, penempatan, dokumen, kompetensi, laporan, administrasi SDM |
| Pimpinan | Monitoring dan keputusan | Dashboard, laporan, data pegawai sesuai kewenangan, approval yang ditugaskan |
| Kepala Unit / Atasan | Mengelola tim dalam unitnya | Direktori tim, ringkasan kehadiran, approval dan penilaian tim sesuai kewenangan |
| Pegawai | Mengakses data pribadi | Profil sendiri, dokumen yang diizinkan, pengajuan cuti/izin, riwayat sendiri jika fitur diaktifkan |

### Ketentuan Hak Akses

- Terapkan RBAC di backend/API, bukan hanya menyembunyikan menu di frontend.
- Terapkan pembatasan lingkup data (*row/record-level scope*) untuk atasan dan kepala unit.
- Pegawai hanya boleh melihat atau mengubah data miliknya sesuai aturan yang ditetapkan.
- Perubahan data sensitif harus mencatat pengguna, waktu, data yang berubah, dan sumber perubahan.
- Jangan mengekspos NIK, nomor kontak pribadi, alamat, dokumen identitas, atau dokumen kepegawaian kepada role yang tidak membutuhkan.
- Akses dokumen harus mengikuti hak akses pada pegawai dan jenis dokumen.

## 7. Struktur Navigasi yang Disarankan

1. Dashboard
2. Data Pegawai
   - Daftar Pegawai
   - Tambah Pegawai
   - Direktori Pegawai
   - Profil Pegawai
3. Organisasi
   - Unit Kerja/Departemen
   - Jabatan
   - Bagan Organisasi
4. Penempatan dan Riwayat
   - Penempatan Aktif
   - Riwayat Jabatan/Penugasan
5. Kehadiran
   - Rekap Kehadiran
   - Data Absensi
   - Koreksi Absensi
6. Cuti dan Izin
   - Pengajuan
   - Persetujuan
   - Jenis Cuti/Izin dan Saldo
7. Kompetensi
   - Master Kompetensi
   - Kompetensi Pegawai
   - Sertifikasi
   - Analisis Kesenjangan Kompetensi
8. Kinerja
   - Periode Penilaian
   - Penilaian Pegawai
   - Riwayat Penilaian
9. Dokumen
10. Laporan
11. Persetujuan dan Notifikasi
12. Pengaturan
   - Pengguna dan Role
   - Referensi Data
   - Konfigurasi Workflow
   - Audit Log

Menu yang belum masuk fase implementasi boleh ditampilkan sebagai nonaktif/placeholder hanya jika berguna untuk demo dan diberi label jelas. Jangan membuat tombol yang tampak aktif tetapi tidak memiliki fungsi.

## 8. Kebutuhan Fungsional

Prioritas:
- **P0:** wajib untuk demo inti.
- **P1:** penting untuk versi operasional berikutnya.
- **P2:** lanjutan atau bergantung pada keputusan client.

### FR-01 — Dashboard SDM (P0)

Dashboard menjadi ringkasan kondisi SDM sesuai role pengguna.

Informasi yang dapat ditampilkan:

- Total pegawai.
- Pegawai aktif dan nonaktif.
- Distribusi pegawai per unit kerja.
- Distribusi pegawai berdasarkan status/jenis kepegawaian, jika kategori sudah disepakati.
- Jumlah pegawai berdasarkan jabatan.
- Ringkasan kompetensi atau sertifikasi.
- Ringkasan kehadiran, jika data absensi tersedia.
- Pengajuan cuti/izin yang menunggu persetujuan, jika workflow sudah aktif.
- Aktivitas terbaru.
- Notifikasi dokumen atau sertifikasi yang akan kedaluwarsa, jika tanggal kedaluwarsa tersedia.

Aturan:
- Angka dashboard harus dihitung dari data yang tersimpan, bukan angka hard-coded.
- Pengguna hanya melihat statistik yang sesuai hak aksesnya.
- Grafik dan kartu statistik harus memiliki label, satuan, dan periode yang jelas.
- Data kosong harus menampilkan empty state, bukan angka palsu.

### FR-02 — Manajemen Data Pegawai (P0)

Admin SDM dapat membuat, melihat, memperbarui, mencari, memfilter, dan mengarsipkan data pegawai.

Data profil minimal yang disarankan:

**A. Identitas dasar**
- ID internal pegawai.
- Nama lengkap.
- Foto profil (opsional).
- NIP/Nomor identitas kepegawaian (opsional sesuai kategori pegawai).
- Kontak/email sesuai kebutuhan.
- Informasi alamat atau kontak darurat hanya jika client menyatakan perlu.

**B. Informasi kepegawaian**
- Jenis/kategori pegawai (master yang dapat dikonfigurasi).
- Status kepegawaian.
- Tanggal mulai bekerja/bergabung.
- Nomor dan tanggal SK/keputusan pengangkatan, jika berlaku.
- Unit kerja.
- Jabatan.
- Atasan langsung.
- Status aktif/nonaktif.

**C. Pendidikan dan pengalaman**
- Riwayat pendidikan.
- Riwayat pengalaman kerja/resume.
- Keahlian/skills.
- Sertifikasi terkait.

**D. Informasi kerja**
- Lokasi kerja.
- Jadwal kerja (jika digunakan).
- Approver/atasan untuk proses tertentu.
- Catatan internal yang dibatasi aksesnya.

Kemampuan daftar pegawai:
- Search berdasarkan nama, ID pegawai, NIP (jika ada), unit, atau jabatan.
- Filter berdasarkan status aktif, kategori pegawai, unit, jabatan, dan rentang tanggal bergabung.
- Sortir dan pagination.
- Detail profil dengan tab/section yang terstruktur.
- Validasi field wajib dan duplikasi ID.
- Import/export hanya untuk role yang berwenang; format dan validasi import harus jelas.
- Arsipkan pegawai ketika tidak aktif; hindari hard delete terhadap data yang memiliki riwayat/transaksi.

Catatan: identitas yang bersifat spesifik ASN, seperti NIP, tidak boleh diwajibkan untuk semua jenis pegawai sebelum kategori tenaga kerja dikonfirmasi.

### FR-03 — Struktur Organisasi dan Jabatan (P0)

Sistem mengelola struktur organisasi secara terpisah dari data pegawai.

Entitas:
- Unit kerja/departemen.
- Jabatan/posisi.
- Hubungan induk-anak antarunit.
- Atasan atau posisi pelapor bila diperlukan.

Kemampuan:
- Tambah, ubah, lihat, dan arsipkan unit kerja.
- Tambah dan ubah jabatan.
- Menentukan unit induk dan unit bawahan.
- Menampilkan organization chart.
- Menampilkan pegawai pada unit/jabatan yang relevan.
- Mencegah struktur relasi yang membentuk siklus.
- Menjaga data historis ketika struktur berubah.

### FR-04 — Penempatan dan Riwayat Jabatan/Penugasan (P0)

Sistem mencatat penempatan aktif dan seluruh riwayat perubahan pegawai.

Jenis riwayat yang dapat didukung:
- Penempatan awal.
- Mutasi/perpindahan unit.
- Promosi/perubahan jabatan.
- Penugasan sementara atau pelaksana tugas.
- Perubahan atasan langsung.
- Perubahan status kepegawaian.

Setiap riwayat minimal menyimpan pegawai, unit, jabatan, tanggal mulai berlaku, tanggal akhir (jika ada), jenis perubahan, dasar/dokumen pendukung (opsional), dan pencatat.

Aturan:
- Maksimal satu penempatan utama aktif pada satu waktu, kecuali aturan organisasi mengizinkan penugasan rangkap.
- Tanggal akhir tidak boleh lebih awal dari tanggal mulai.
- Perubahan baru tidak menghapus riwayat lama.
- Profil pegawai menampilkan penempatan saat ini dan timeline riwayat.

### FR-05 — Absensi dan Kehadiran (P1)

Fitur ini mengelola kehadiran pegawai apabila metode absensi telah disepakati client.

Kemampuan yang direncanakan:
- Input atau impor catatan kehadiran.
- Waktu masuk dan pulang.
- Status hadir, terlambat, izin, sakit, dinas luar, tanpa keterangan, atau kategori lain yang dapat dikonfigurasi.
- Rekap harian, mingguan, dan bulanan.
- Filter pegawai/unit/periode/status.
- Koreksi absensi dengan alasan dan approval jika diperlukan.
- Riwayat koreksi.
- Tampilan kalender atau tabel kehadiran.

Sumber data yang mungkin:
- Input admin SDM.
- Self-service pegawai.
- Import file.
- Integrasi perangkat fingerprint/attendance machine melalui connector.

**TBD:** metode resmi absensi, perangkat yang digunakan, kebijakan keterlambatan, jam kerja, shift, dan apakah pegawai dapat melakukan clock-in melalui aplikasi. Jangan mengklaim integrasi fingerprint sudah berjalan sebelum perangkat dan protokolnya diuji.

### FR-06 — Cuti dan Izin (P1)

Pegawai dapat mengajukan cuti/izin dan pihak berwenang dapat memprosesnya.

Kemampuan:
- Master jenis cuti/izin.
- Aturan saldo atau kuota jika berlaku.
- Pengajuan dengan tanggal mulai, tanggal selesai, alasan, dan lampiran opsional.
- Validasi benturan tanggal dan kelengkapan pengajuan.
- Alur persetujuan oleh atasan/admin sesuai konfigurasi.
- Status: draft, diajukan, disetujui, ditolak, dibatalkan, atau status lain yang diperlukan.
- Catatan keputusan dan tanggal keputusan.
- Kalender cuti/izin.
- Riwayat pengajuan pegawai.
- Notifikasi kepada pemohon dan approver.

Aturan:
- Saldo cuti hanya dihitung jika kebijakan kuota/saldo sudah ditetapkan.
- Jenis cuti, syarat lampiran, dan urutan approver bersifat konfigurabel.
- Sistem tidak boleh menetapkan aturan cuti ASN/non-ASN tanpa konfirmasi client.

### FR-07 — Kompetensi, Skills, dan Sertifikasi (P0)

Fitur kompetensi merupakan salah satu pembeda utama SI-SDM dan harus diprioritaskan.

Kemampuan:
- Mengelola master kompetensi/keahlian.
- Mengelompokkan kompetensi berdasarkan kategori.
- Menentukan level kompetensi (misalnya dasar, menengah, mahir) melalui master yang dapat diubah.
- Menghubungkan kompetensi dengan pegawai.
- Menyimpan level, tanggal penilaian/verifikasi, penilai/verifikator, dan catatan.
- Menyimpan bukti kompetensi atau sertifikat.
- Menyimpan nomor sertifikat, penerbit, tanggal terbit, dan tanggal kedaluwarsa jika relevan.
- Menampilkan kompetensi per pegawai.
- Memfilter pegawai berdasarkan kompetensi dan level.
- Memetakan kompetensi yang dibutuhkan untuk suatu jabatan/unit.
- Menampilkan kesenjangan kompetensi (*competency gap*) antara kebutuhan dan kondisi aktual.

Integrasi SI-DIKLAT:
- SI-DIKLAT menjadi pemilik data kegiatan pelatihan, materi, peserta, dan hasil penyelesaian pelatihan.
- SI-SDM menjadi pemilik profil kompetensi pegawai.
- Hasil pelatihan dari SI-DIKLAT dapat diusulkan sebagai bukti/pembaruan kompetensi di SI-SDM.
- Pembaruan kompetensi dari hasil pelatihan perlu aturan verifikasi yang disepakati; jangan otomatis menaikkan level kompetensi hanya karena pegawai mengikuti pelatihan.

### FR-08 — Penilaian Kinerja (P1/P2)

Kemampuan yang dapat disiapkan:
- Periode penilaian.
- Target atau indikator kinerja.
- Form penilaian.
- Penilaian oleh atasan/penilai.
- Catatan umpan balik.
- Hasil dan riwayat penilaian.
- Status proses penilaian.

**Batas penting:** implementasi awal hanya boleh diposisikan sebagai monitoring/penilaian internal apabila belum ada konfirmasi bahwa SI-SDM harus menggantikan atau terintegrasi dengan sistem penilaian resmi ASN, SKP, atau e-Kinerja. Format, indikator, skala, dan alur persetujuan wajib mengikuti ketentuan client.

### FR-09 — Dokumen Kepegawaian (P0)

Kemampuan:
- Upload, lihat, unduh, dan arsipkan dokumen sesuai hak akses.
- Mengelompokkan dokumen berdasarkan jenis.
- Menghubungkan dokumen dengan pegawai.
- Menyimpan nomor dokumen, tanggal terbit, penerbit, tanggal kedaluwarsa jika ada, dan catatan.
- Menampilkan daftar dokumen pada profil pegawai.
- Pencarian/filter berdasarkan pegawai dan jenis dokumen.
- Mencatat aktivitas upload, penggantian, dan penghapusan/arsip.

Jenis dokumen contoh (dapat dikonfigurasi): SK pengangkatan, surat tugas, kontrak/perjanjian, sertifikat, ijazah, dokumen penilaian, dan dokumen pendukung lainnya.

Keamanan:
- Batasi akses file berdasarkan role dan pegawai.
- Validasi jenis dan ukuran file.
- Simpan file di storage yang tidak dapat diakses publik secara langsung.
- Gunakan URL unduhan sementara/terproteksi bila didukung arsitektur.

### FR-10 — Riwayat Karier dan Lifecycle Pegawai (P1)

Sistem menampilkan timeline peristiwa kepegawaian, seperti mulai bergabung, perubahan status, perubahan unit/jabatan, sertifikasi, dan penugasan.

Onboarding/offboarding dapat disediakan sebagai checklist:
- Aktivitas sebelum/ketika pegawai mulai bekerja.
- Penyerahan dokumen atau perlengkapan.
- Checklist penyelesaian tugas ketika pegawai keluar/nonaktif.
- Pengembalian aset yang berkoordinasi dengan SI-ASET.

Fitur onboarding/offboarding tidak boleh menggantikan proses formal yang belum ditetapkan client.

### FR-11 — Laporan SDM (P1)

Laporan yang direncanakan:
- Daftar pegawai aktif/nonaktif.
- Rekap pegawai per unit dan jabatan.
- Rekap jenis/status kepegawaian.
- Riwayat penempatan/jabatan.
- Rekap kompetensi dan sertifikasi.
- Sertifikasi yang akan kedaluwarsa.
- Rekap kehadiran.
- Rekap cuti/izin.
- Hasil penilaian internal jika fitur digunakan.

Laporan harus dapat difilter berdasarkan periode dan unit, dengan opsi ekspor PDF/Excel jika disetujui. Data yang diekspor harus mengikuti hak akses pengguna.

### FR-12 — Notifikasi dan Persetujuan (P1)

- Menampilkan daftar tindakan yang menunggu persetujuan.
- Notifikasi pengajuan cuti/izin kepada approver.
- Notifikasi hasil persetujuan kepada pemohon.
- Pengingat masa berlaku sertifikasi/dokumen jika tanggal kedaluwarsa tersedia.
- Menyediakan riwayat keputusan dan komentar.
- Kanal notifikasi (in-app, email, WhatsApp, atau lainnya) ditentukan kemudian; MVP cukup menggunakan notifikasi dalam aplikasi bila belum ada integrasi eksternal.

### FR-13 — Pengguna, Role, dan Audit Log (P0)

- Akun pengguna dikelola terpisah dari data induk pegawai.
- Satu akun dapat ditautkan ke satu pegawai jika pengguna merupakan pegawai.
- Role dan permission dapat dikelola admin yang berwenang.
- Reset password dan aktivasi/nonaktif akun.
- Catat aktivitas login penting dan perubahan data penting.
- Audit log memuat pelaku, waktu, aksi, entitas/record, dan ringkasan perubahan.
- Perubahan role/permission harus tercatat.
- Jangan menyimpan password dalam bentuk plaintext.

### FR-14 — Integrasi Modul SI-AH TERPADU (P1 untuk kontrak; realisasi bertahap)

| Modul | Data yang dapat disediakan SI-SDM | Data yang dapat diterima SI-SDM |
|---|---|---|
| SI-LAYAN | ID, nama, jabatan, unit, status aktif, kompetensi yang relevan | Referensi penugasan/jadwal layanan jika diperlukan untuk timeline pegawai |
| SI-ASET | ID dan identitas pegawai, unit, jabatan | Informasi aset/perlengkapan yang ditugaskan kepada pegawai untuk ditampilkan pada profil (opsional) |
| SI-PBJP | ID, nama, unit, jabatan, status aktif | Referensi penugasan/tanggung jawab pengadaan jika diperlukan |
| SI-KEU | Rekap agregat kebutuhan/jumlah SDM sesuai kewenangan | Tidak ada data payroll individual secara default; kebutuhan anggaran SDM hanya jika disetujui |
| SI-DIKLAT | Profil pegawai dan referensi kompetensi yang dibutuhkan | Riwayat pelatihan, keikutsertaan, hasil, dan sertifikat yang tervalidasi |
| SI-GREEN | ID, nama, unit, jabatan, kompetensi terkait | Referensi tanggung jawab/program lingkungan jika diperlukan untuk riwayat pegawai |

Aturan integrasi:
- Gunakan ID pegawai internal yang stabil sebagai identifier utama.
- Pertukaran data dilakukan melalui API/kontrak integrasi yang terdokumentasi, bukan akses langsung ke database modul lain.
- Tetapkan pemilik (*data owner*) untuk setiap field.
- Modul konsumen tidak boleh mengubah master pegawai melalui endpoint baca.
- Perubahan data penting dapat dipublikasikan sebagai event/webhook atau tersedia melalui API sesuai arsitektur yang dipilih.
- Terapkan autentikasi antarlayanan, otorisasi, validasi payload, logging, dan mekanisme retry/idempotency yang sesuai.
- Hindari mengirim data pribadi yang tidak dibutuhkan modul penerima.
- Sediakan status sinkronisasi dan penanganan kegagalan integrasi.

## 9. Aturan Bisnis Global

1. Setiap pegawai mempunyai ID internal unik yang tidak berubah sepanjang siklus data.
2. NIP atau nomor identitas eksternal bukan identifier wajib bagi semua pegawai.
3. Data pegawai tidak dihapus permanen jika sudah terhubung dengan riwayat, dokumen, absensi, atau proses lain; gunakan arsip/nonaktif.
4. Unit kerja dan jabatan merupakan master data terpisah dari pegawai.
5. Penempatan dan jabatan aktif harus dapat ditelusuri berdasarkan tanggal berlaku.
6. Perubahan data master dan data sensitif harus memiliki audit trail.
7. Data yang ditampilkan pada setiap role mengikuti hak akses dan lingkup organisasi.
8. Master jenis pegawai, status, kompetensi, dokumen, cuti, dan status workflow harus dapat dikonfigurasi atau disepakati sebelum implementasi final.
9. Data demo harus diberi label sebagai data simulasi dan tidak boleh dicampur dengan data produksi.
10. Jangan menganggap seluruh pegawai mempunyai akun login; hubungan akun dan pegawai bersifat opsional.

## 10. Model Data Konseptual

Model berikut merupakan rancangan awal, bukan skema database final. Agent harus menyesuaikannya dengan stack, pola repository, dan keputusan integrasi yang sudah ada.

| Entitas | Fungsi |
|---|---|
| employees | Data induk pegawai dan status dasar |
| employee_contacts | Kontak pegawai, bila diperlukan |
| employee_educations | Riwayat pendidikan |
| employee_experiences | Riwayat pengalaman/resume |
| employee_documents | Metadata dan referensi file dokumen |
| employment_types | Master jenis/kategori pegawai |
| employment_statuses | Master status kepegawaian |
| departments / organizational_units | Unit kerja dan relasi hierarki |
| job_positions | Master jabatan/posisi |
| employee_assignments | Riwayat unit, jabatan, atasan, dan periode penempatan |
| competencies | Master kompetensi |
| competency_categories | Kategori kompetensi |
| employee_competencies | Kompetensi pegawai, level, verifikator, dan bukti |
| certifications | Data sertifikasi pegawai |
| attendance_records | Catatan kehadiran |
| attendance_adjustments | Permintaan/perubahan koreksi absensi |
| leave_types | Master cuti/izin |
| leave_requests | Pengajuan dan keputusan cuti/izin |
| leave_balances | Saldo cuti jika kebijakan menggunakannya |
| performance_periods | Periode penilaian |
| performance_reviews | Hasil penilaian internal |
| users | Akun autentikasi |
| roles / permissions / user_roles | Hak akses |
| approval_steps / approval_actions | Konfigurasi dan riwayat approval |
| notifications | Notifikasi dalam aplikasi |
| audit_logs | Jejak aktivitas |
| integration_logs | Status pertukaran data dengan modul lain |

Relasi penting:
- `employees` menjadi pusat relasi profil pegawai.
- `users` terpisah dari `employees`; akun pegawai tidak wajib.
- `employee_assignments` menyimpan riwayat, sedangkan penempatan saat ini ditentukan dari record yang aktif/berlaku.
- Kompetensi pegawai tidak disimpan sebagai daftar teks bebas saja; gunakan relasi ke master `competencies`.
- Dokumen disimpan sebagai metadata dan referensi storage, bukan blob publik di record pegawai.
- Gunakan foreign key dan constraint untuk menjaga integritas referensi.

## 11. Kebutuhan UI/UX

### 11.1 Gaya Antarmuka

Gunakan pendekatan dashboard HRIS modern seperti PlatKa dan pola profil terstruktur seperti Odoo:

- Sidebar navigasi yang konsisten.
- Header berisi judul halaman, breadcrumb, dan menu pengguna.
- Dashboard dengan kartu statistik, grafik sederhana, aktivitas, dan approval.
- Tabel data dengan search, filter, sorting, pagination, dan aksi yang jelas.
- Form menggunakan label yang jelas, validasi inline, dan grouping berdasarkan bagian.
- Profil pegawai menggunakan tab/section: Ringkasan, Informasi Pekerjaan, Pendidikan & Pengalaman, Kompetensi, Dokumen, Riwayat.
- Organization chart mudah dibaca dan tetap memiliki alternatif tampilan tabel/list.
- Status workflow menggunakan label yang konsisten.
- Sediakan loading, empty, error, dan success state.
- Responsif untuk desktop dan tablet; fungsi inti tetap bisa digunakan di layar lebih kecil.

### 11.2 Prinsip Demo

- Gunakan data simulasi yang masuk akal dan konsisten antarhalaman.
- Dashboard harus sesuai dengan isi tabel/detail pegawai.
- Jangan menampilkan angka seperti 1.247 pegawai atau Rp 8,5 miliar sebagai data riil Asrama Haji.
- Tandai data simulasi pada environment demo.
- Tombol dan aksi utama harus benar-benar bekerja pada demo, walaupun belum terhubung ke integrasi eksternal.

## 12. Skenario Demo yang Disarankan

Urutan demonstrasi SI-SDM:

1. Login sebagai Admin SDM.
2. Tampilkan Dashboard: jumlah pegawai, distribusi unit, ringkasan kompetensi, dan aktivitas.
3. Buka daftar pegawai; lakukan pencarian/filter.
4. Buka profil satu pegawai dan tunjukkan informasi pekerjaan, unit, jabatan, serta atasan.
5. Tampilkan riwayat penempatan/jabatan.
6. Tampilkan organization chart.
7. Buka kompetensi pegawai, level, sertifikat, dan bukti pendukung.
8. Tunjukkan dokumen kepegawaian.
9. Tunjukkan contoh alur cuti/izin atau approval jika sudah diimplementasikan.
10. Tunjukkan bagaimana data pegawai yang sama dapat direferensikan oleh modul lain melalui konsep API/integrasi.

Alur demo utama sebaiknya menonjolkan **data pegawai → penempatan → kompetensi → pemanfaatan data oleh modul lain**. Absensi, payroll, dan kasbon tidak perlu menjadi fokus demo awal.

## 13. Kriteria Penerimaan (Acceptance Criteria)

### Data Pegawai
- Admin SDM dapat membuat pegawai dengan ID internal unik.
- Sistem menolak duplikasi ID internal.
- Admin dapat mencari dan memfilter pegawai.
- Profil menampilkan data sesuai hak akses.
- Pegawai yang dinonaktifkan tetap memiliki riwayat yang dapat ditelusuri oleh role berwenang.

### Organisasi dan Penempatan
- Admin dapat membuat unit kerja dan jabatan.
- Sistem dapat menampilkan bagan organisasi.
- Perubahan penempatan membuat riwayat baru dan tidak menghapus riwayat lama.
- Tanggal penempatan divalidasi.

### Kompetensi
- Admin dapat membuat master kompetensi.
- Pegawai dapat memiliki beberapa kompetensi dengan level dan bukti.
- Sistem dapat menampilkan daftar pegawai berdasarkan kompetensi.
- Sertifikasi dapat memiliki tanggal kedaluwarsa jika relevan.

### Dokumen dan Keamanan
- Dokumen dapat diunggah dan ditautkan ke pegawai.
- Pengguna tanpa izin tidak dapat melihat atau mengunduh dokumen terbatas.
- Aktivitas perubahan data penting tercatat di audit log.

### Dashboard
- Ringkasan dihitung dari data aktual pada database demo.
- Filter/lingkup data mengikuti role.
- Kondisi kosong ditampilkan dengan benar.

### Integrasi
- Setiap pegawai memiliki ID stabil yang dapat digunakan modul lain.
- Kontrak API (minimal dokumentasi endpoint dan contoh payload) tersedia sebelum integrasi lintas modul.
- Endpoint baca data pegawai tidak mengizinkan konsumen mengubah master pegawai.

## 14. Kebutuhan Nonfungsional

- **Keamanan:** autentikasi aman, otorisasi server-side, password hashing, validasi input, proteksi file, dan pembatasan akses data pribadi.
- **Privasi:** minimalkan pengumpulan data pribadi; dokumentasikan tujuan penggunaan dan role yang dapat mengaksesnya. Perhatikan ketentuan perlindungan data pribadi yang berlaku.
- **Auditability:** perubahan penting harus dapat ditelusuri.
- **Reliabilitas:** kegagalan proses upload, approval, atau integrasi harus memberikan pesan yang jelas dan tidak membuat data setengah tersimpan.
- **Integritas data:** gunakan transaksi database untuk proses multi-record yang harus atomik.
- **Maintainability:** pisahkan domain pegawai, organisasi, kompetensi, absensi, cuti, dan integrasi agar bisa dikembangkan bertahap.
- **Interoperability:** API memiliki autentikasi, validasi, dokumentasi, versi, dan format respons konsisten.
- **Accessibility/usability:** label form jelas, kontras memadai, status tidak hanya dibedakan dengan warna, dan navigasi keyboard dasar didukung.
- **Backup:** backup database dan file dokumen perlu direncanakan bersama deployment.

## 15. Risiko dan Hal yang Masih Harus Dikonfirmasi (TBD)

Jangan mengunci keputusan berikut tanpa konfirmasi client:

1. Apakah SI-SDM mengelola pegawai internal UPT saja atau juga tenaga outsourcing, cleaning service, keamanan, teknisi, dan tenaga pendukung?
2. Berapa jumlah pegawai dan bagaimana kategori/status kepegawaiannya?
3. Apakah tersedia data pegawai lama dalam Excel atau sistem lain untuk migrasi?
4. Apa struktur organisasi resmi, nama unit, jabatan, dan hubungan pelaporannya?
5. Apakah pegawai akan memiliki akun dan mengakses portal sendiri?
6. Bagaimana metode absensi: fingerprint, aplikasi, input admin, atau kombinasi?
7. Apakah cuti/izin dikelola di SI-SDM? Apa jenis, kuota, dan alur approval resminya?
8. Apakah penilaian kinerja diperlukan? Apakah internal atau mengikuti sistem resmi ASN/e-Kinerja/SKP?
9. Apa standar kompetensi yang digunakan dan siapa yang berwenang memverifikasinya?
10. Apakah payroll dan kasbon benar-benar termasuk kebutuhan SI-SDM atau dikelola sistem lain?
11. Apakah ada sistem/API resmi yang harus diintegrasikan?
12. Siapa pemilik dan pengelola data pada masing-masing modul SI-AH TERPADU?
13. Kebijakan retensi, akses, dan penghapusan dokumen seperti apa yang berlaku?

## 16. Rekomendasi Tahapan Implementasi

### Tahap 1 — Demo Core SI-SDM

- Dashboard dengan data simulasi yang terhubung.
- CRUD dan daftar pegawai.
- Profil pegawai.
- Master unit kerja dan jabatan.
- Organization chart.
- Riwayat penempatan/jabatan.
- Master dan profil kompetensi.
- Dokumen pegawai.
- Role dasar dan audit log dasar.

### Tahap 2 — Operasional SDM

- Absensi dan rekap.
- Cuti/izin dan approval.
- Notifikasi.
- Laporan SDM.
- Lifecycle/onboarding/offboarding.

### Tahap 3 — Integrasi dan Analitik

- Integrasi SI-DIKLAT untuk riwayat pelatihan dan bukti kompetensi.
- Integrasi SI-LAYAN, SI-ASET, SI-PBJP, SI-KEU, dan SI-GREEN sesuai kebutuhan.
- Competency gap analysis.
- Penilaian kinerja internal bila disetujui.
- Attendance machine bridge jika perangkat dan protokol tersedia.

### Tahap 4 — Fitur Opsional

- Payroll.
- Kasbon/pinjaman.
- Rekrutmen.
- Integrasi e-Kinerja/SKP atau layanan eksternal lain.

Tahap 4 hanya dilakukan setelah ruang lingkup, pemilik proses, aturan bisnis, dan kebutuhan integrasi dikonfirmasi.

## 17. Instruksi untuk Coding Agent

Gunakan dokumen ini sebagai baseline kebutuhan SI-SDM, dengan instruksi berikut:

1. **Audit repository terlebih dahulu.** Identifikasi framework, struktur folder, pola routing, komponen UI, autentikasi, database, migration, dan konvensi coding yang sudah ada.
2. Jangan mengganti stack atau merombak arsitektur proyek tanpa alasan kuat dan persetujuan.
3. Implementasikan SI-SDM saja. Jangan membangun tujuh modul SI-AH sekaligus.
4. Prioritaskan Tahap 1. Jangan mulai payroll, kasbon, atau integrasi eksternal sebelum diminta.
5. Buat skema data yang menjaga riwayat dan integritas referensi. Hindari menyimpan semua riwayat sebagai teks bebas.
6. Pisahkan akun `users` dari master `employees`; tautkan bila relevan.
7. Terapkan otorisasi di server/backend. Menyembunyikan tombol di frontend tidak cukup.
8. Pastikan dashboard menggunakan query/data nyata dari database demo, bukan angka statis yang tidak terkait record.
9. Gunakan data dummy yang konsisten dan jelas ditandai sebagai simulasi.
10. Jangan membuat aturan ASN, cuti, payroll, atau penilaian resmi berdasarkan asumsi. Implementasikan konfigurasi generik atau tandai TBD.
11. Untuk integrasi, buat boundary/service/API contract yang terdokumentasi. Jangan mengakses database modul lain secara langsung.
12. Buat validasi, loading/empty/error state, dan pesan sukses/gagal untuk seluruh aksi penting.
13. Sediakan migration, seed data, dan instruksi menjalankan aplikasi sesuai pola repository.
14. Tambahkan pengujian untuk aturan penting: ID unik, hak akses, riwayat penempatan, workflow approval, dan akses dokumen.
15. Setelah implementasi, laporkan fitur yang selesai, fitur yang belum, asumsi yang dipakai, cara menjalankan, dan hasil pengujian. Jangan mengklaim integrasi eksternal berfungsi jika belum benar-benar diuji.

### Definition of Done — Tahap 1

Tahap 1 dianggap selesai apabila:
- Admin dapat login dan mengakses menu sesuai role.
- CRUD/list/detail pegawai berfungsi.
- Unit kerja dan jabatan dapat dikelola.
- Organization chart menampilkan struktur dari data database.
- Riwayat penempatan tersimpan dan dapat ditampilkan.
- Kompetensi dapat dikelola dan ditautkan ke pegawai.
- Dokumen pegawai dapat diunggah dan aksesnya terlindungi.
- Dashboard menampilkan ringkasan berdasarkan data demo.
- Data penting memiliki validasi dan audit dasar.
- Migration, seed, dan petunjuk menjalankan aplikasi tersedia.

## 18. Referensi

1. **HRIS PlatKa — Demo desain**: https://hris-demo-design.platkadigital.com/  
   Digunakan sebagai referensi layout/dashboard. Halaman menyatakan demo hanya untuk tujuan desain dan bukan software fungsional penuh.
2. **HRIS PlatKa — Halaman produk**: https://platka.io/id/products/hris-workforce-absensi-payroll-integrasi-ke-banyak-mesin-attendance  
   Digunakan sebagai referensi cakupan HRIS: data pegawai, struktur, absensi, cuti, payroll, kasbon, dokumen, laporan, audit trail, dan kontrol akses.
3. **Odoo Employees — Product page**: https://www.odoo.com/app/employees  
   Digunakan sebagai referensi manajemen profil dan informasi pegawai.
4. **Odoo Employees — Features**: https://www.odoo.com/app/employees-features  
   Digunakan sebagai referensi skills, resume, org chart, work information, approvers, schedules, onboarding/offboarding, appraisal, time off, dan dokumen.
5. **Odoo 19 — Employees documentation**: https://www.odoo.com/documentation/19.0/applications/hr/employees.html  
   Digunakan sebagai referensi konfigurasi dan fitur Employees pada Odoo 19.

---

**Catatan akhir:** PRD ini merupakan baseline untuk demo dan perancangan awal. Bagian yang berstatus TBD harus dikonfirmasi kepada client sebelum diperlakukan sebagai aturan operasional resmi.
