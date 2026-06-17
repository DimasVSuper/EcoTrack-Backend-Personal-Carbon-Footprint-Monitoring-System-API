# FASE 7 DEPLOYMENT AND MAINTENANCE

## 7.1 Deployment

Proses deployment aplikasi EcoTrack melibatkan dua komponen utama: backend Laravel dan aplikasi mobile Flutter, dengan konfigurasi standar untuk server lokal dan distribusi produksi.

### 7.1.1 Deployment Backend Laravel

#### Kebutuhan Server
1. PHP 8.2 atau lebih tinggi
2. Composer (versi terbaru)
3. MySQL 5.7+ atau PostgreSQL 10+ (untuk produksi)
4. SQLite 3 (untuk pengembangan)
5. Laravel 11.x framework
6. Web Server (Apache/Nginx)
7. Sertifikat SSL (untuk produksi)

#### Langkah-Langkah Instalasi
1. Clone Repository & Instalasi Dependencies
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

2. Konfigurasi Environment
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Contoh konfigurasi dasar:
   ```env
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://127.0.0.1:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=ecotrack
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. Migrasi Database dan Seeding
   ```bash
   php artisan migrate --force
   php artisan db:seed
   ```

4. Konfigurasi Storage
   ```bash
   php artisan storage:link
   chmod -R 775 storage bootstrap/cache
   ```

5. Optimasi Cache
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

6. Menjalankan Server
   - Pengembangan:
     ```bash
     php artisan serve --host=0.0.0.0 --port=8000
     ```
   - Produksi:
     Konfigurasi Apache/Nginx dengan document root ke folder `public/`.

#### Checklist Produksi
1. Set `APP_ENV=production` dan `APP_DEBUG=false`
2. Gunakan SSL certificate (Let’s Encrypt / commercial)
3. Set permission folder `storage/` dan `bootstrap/cache/` dengan benar
4. Aktifkan log harian untuk monitoring
5. Atur backup database secara rutin
6. Pastikan API berjalan pada domain yang aman
7. Jalankan cache optimasi pada server produksi

---

### 7.1.2 Deployment Aplikasi Mobile Flutter

#### Lingkungan Pengembangan
1. Flutter SDK terbaru
2. Dart SDK sesuai versi Flutter
3. Android Studio / VS Code dengan Flutter extension
4. Xcode (hanya untuk macOS jika deploy iOS)

#### Instalasi Dependencies
```bash
flutter pub get
flutter pub upgrade
```

#### Konfigurasi Environment
1. Base URL API:
   - Emulator Android: `http://10.0.2.2:8000/api`
   - Simulator iOS: `http://localhost:8000/api`
   - Perangkat Fisik: `http://192.168.x.x:8000/api`
   - Produksi: `https://api.domain.com/api`

2. Konfigurasi keamanan token:
   - Token disimpan menggunakan `flutter_secure_storage`
   - Authorization header otomatis ditambahkan saat request API

#### Deployment Android
1. Build APK/AAB:
   ```bash
   flutter build apk --release
   flutter build appbundle --release
   ```
2. Lokasi output:
   - APK: `build/app/outputs/flutter-apk/app-release.apk`
   - AAB: `build/app/outputs/bundle/release/app-release.aab`

#### Deployment iOS
1. Pastikan sertifikat Apple Developer valid
2. Build IPA:
   ```bash
   flutter build ios --release
   ```

#### Pengujian Cross-Platform
```bash
flutter test
flutter analyze
flutter doctor -v
```

#### Target Deployment
1. Google Play Console (Android) - format AAB
2. Apple App Store (iOS) - IPA via Xcode/Transporter
3. Distribusi APK langsung untuk testing internal

---

## 7.2 Maintenance

Pemeliharaan berkelanjutan dilakukan agar sistem EcoTrack tetap aman, stabil, dan dapat diandalkan.

### 7.2.1 Monitoring Sistem

#### Monitoring Log
1. Backend Laravel log:
   - `storage/logs/laravel.log`
   - atau file harian `laravel-YYYY-MM-DD.log`
2. Cek log real-time:
   ```bash
   tail -f storage/logs/laravel.log
   ```
3. Cari error:
   ```bash
   grep -i "error" storage/logs/laravel.log
   ```

#### Pelacakan Error Aplikasi
- Error response API 4xx / 5xx
- Kegagalan query database
- Error autentikasi / otorisasi
- Masalah proses upload/storage
- Error pada integrasi frontend-backend

#### Monitoring Performa
- Waktu respons API
- Penggunaan CPU, memory, dan disk
- Stabilitas koneksi database
- Konsumsi bandwidth

---

### 7.2.2 Pemeliharaan Database

#### Prosedur Backup
```bash
mysqldump -u root -p ecotrack_db > backup_$(date +%Y%m%d).sql
```

#### Kebijakan Retensi Backup
- Backup harian: simpan 7 hari terakhir
- Backup mingguan: simpan 4 minggu terakhir
- Backup bulanan: simpan 12 bulan terakhir

#### Manajemen Migrasi
```bash
php artisan migrate:status
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

#### Pemeriksaan Integrasi Data
- Verifikasi foreign key constraint
- Periksa record yang tidak konsisten
- Validasi data pengguna, log transport, dan log listrik
- Pantau ukuran database dan performa query

---

### 7.2.3 Prosedur Update

#### Update Backend (Laravel)
```bash
composer update --no-dev
composer audit
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

#### Update Aplikasi Mobile (Flutter)
```bash
flutter upgrade
flutter doctor -v
flutter pub upgrade
flutter pub outdated
flutter build apk --release
```

#### Manajemen Versi
- Backend: update versi di `composer.json`
- Mobile: increment version dan build number di `pubspec.yaml`
- Kelola changelog pada dokumen proyek

---

### 7.2.4 Pemeliharaan Keamanan

#### Rotasi Kredensial
- Password database secara berkala
- Token API / key aplikasi secara berkala
- Sertifikat SSL sebelum expired

#### Patch Keamanan
- Monitor advisory keamanan Laravel
- Terapkan patch kritis dalam 24–48 jam
- Uji update keamanan di staging dahulu
- Jalankan `composer audit` secara rutin

#### Kontrol Akses
- Review izin pengguna secara berkala
- Nonaktifkan akun tidak aktif
- Pantau percobaan login yang gagal
- Audit tindakan admin / user level tinggi

---

### 7.2.5 Perbaikan Bug & Resolusi Masalah

#### Alur Pelacakan Bug
1. Identifikasi masalah dari laporan pengguna, log error, atau alert monitoring
2. Klasifikasi prioritas:
   - Kritis: sistem down, kehilangan data, keamanan
   - Tinggi: fungsi utama rusak
   - Sedang: fitur minor
   - Rendah: kosmetik
3. Prosedur pengujian:
   ```bash
   php artisan test
   flutter test
   flutter analyze
   ```
4. Deployment perbaikan:
   - Backend: deploy via git pull + composer install + migrate
   - Mobile: rebuild APK/AAB lalu rilis ke store atau distribusi internal

#### Update Dokumentasi
- Update dokumentasi API
- Update panduan troubleshooting
- Catat perubahan pada changelog proyek

---

### 7.2.6 Pemeriksaan Kesehatan Sistem

#### Pemeriksaan Mingguan
1. Verifikasi semua endpoint API merespons
2. Periksa stabilitas koneksi database
3. Uji integrasi autentikasi dan API utama
4. Pastikan storage dan upload berjalan normal
5. Cek penggunaan disk space

#### Pemeriksaan Bulanan
1. Review log error untuk masalah berulang
2. Analisis pola penggunaan API
3. Periksa tanggal kedaluwarsa sertifikat SSL
4. Verifikasi backup restore procedure
5. Update dependencies dengan patch keamanan

#### Mode Pemeliharaan
```bash
php artisan down --secret="maintenance-token"
# lakukan update / migrasi / maintenance
php artisan up
```

Pemeliharaan yang konsisten memastikan sistem EcoTrack tetap andal, aman, dan siap digunakan dalam pengelolaan jejak karbon serta aktivitas pengguna.
