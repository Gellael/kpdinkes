# 🏥 Sistem Informasi Layanan Kesehatan (Portal BPJS & Ambulan)
**Dinas Kesehatan Provinsi Bengkulu**

Sistem informasi berbasis web yang dirancang khusus untuk memfasilitasi program prioritas Gubernur dan Wakil Gubernur. Aplikasi ini mengintegrasikan layanan pendaftaran BPJS oleh petugas Puskesmas dan pelaporan log operasional armada Ambulan secara *real-time*, lengkap dengan panel pengawasan untuk Administrator.

---

## ✨ Fitur Unggulan

### 🔐 Multi-Role Access Control
Sistem ini memiliki 3 hak akses pengguna dengan *dashboard* dan fungsi yang terisolasi:
1. **Administrator:** Memantau seluruh pendaftaran BPJS, melihat log perjalanan ambulan, *export* laporan ke Excel, dan mengelola akun pengguna (CRUD).
2. **Petugas Puskesmas:** Menginput data warga untuk pendaftaran BPJS dan mengunggah dokumen persyaratan (KTP & KK).
3. **Driver Ambulan:** Melaporkan log perjalanan armada, titik jemput/tujuan, pasien yang dibawa, dan bukti operasional.

### 🛡️ Keamanan File Tingkat Tinggi (Anti-IDOR)
Dokumen sensitif milik warga (KTP & KK) tidak disimpan di folder *public* biasa. Sistem menggunakan sistem *Private Storage* dengan *Controller* khusus untuk menampilkan gambar, sehingga data tidak bisa diakses sembarangan melalui *URL guessing* (mencegah kebocoran data pribadi).

### 📱 UI/UX Modern & Responsive (SaaS Style)
- Desain antarmuka menggunakan gaya *Software as a Service* (SaaS) yang bersih dan profesional.
- **Mobile First:** Tabel data otomatis berubah menjadi bentuk "Kartu (Cards)" saat dibuka melalui *smartphone*.
- **Pencarian Real-Time:** Filter data instan menggunakan JavaScript murni tanpa perlu me-*refresh* halaman.
- **SweetAlert2:** Konfirmasi hapus data yang elegan dan aman.
- **Pop-up Preview:** Fitur melihat dokumen KTP/KK tanpa perlu berpindah halaman (menggunakan Modal Bootstrap).

### 📊 Pelaporan & Export
Fitur *export* data ke dalam format Excel (.csv) untuk kebutuhan pelaporan rekapitulasi harian/bulanan Dinas Kesehatan.

---

## 🛠️ Teknologi yang Digunakan
- **Framework:** Laravel (PHP)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript Vanilla
- **Font:** [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans)
- **Icons:** FontAwesome 6

---

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer atau laptop Anda:

### 1. Persiapan Kebutuhan Sistem
Pastikan komputer Anda sudah terinstal:
- [PHP](https://www.php.net/downloads) (Minimal versi 8.1)
- [Composer](https://getcomposer.org/download/)
- [MySQL/XAMPP](https://www.apachefriends.org/download.html)
- [Git](https://git-scm.com/downloads)

### 2. Kloning Repositori
Buka Terminal/Command Prompt, dan jalankan perintah berikut:
```bash
git clone [https://github.com/Gellael/kpdinkes.git](https://github.com/Gellael/kpdinkes.git)
cd kpdinkes
