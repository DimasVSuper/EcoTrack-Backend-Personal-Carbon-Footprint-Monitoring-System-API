# EcoTrack Backend - Personal Carbon Footprint Monitoring System API

EcoTrack adalah platform monitoring jejak karbon pribadi yang dirancang untuk membantu pengguna mencatat, menghitung, dan menganalisis emisi karbon harian yang dihasilkan dari aktivitas transportasi dan penggunaan listrik rumah tangga. Proyek ini dibangun untuk mendukung pencapaian **SDG 13: Climate Action**.

Repository ini merupakan *source code* bagian **Backend API** yang dibangun menggunakan framework Laravel.

---

## 🚀 Fitur Utama (MVP Scope)

- **Autentikasi Aman (Laravel Sanctum):** Registrasi, login, dan logout berbasis token (*Stateless Bearer Token*).
- **Pencatatan Aktivitas & Kalkulasi Otomatis:**
  - **Modul Transportasi:** Menghitung emisi berdasarkan jarak tempuh (km) dan faktor emisi spesifik jenis kendaraan.
  - **Modul Listrik:** Menghitung emisi berdasarkan penggunaan daya listrik (kWh) per periode.
- **CRUD Riwayat:** Mendukung create, read, update, dan delete untuk log transportasi serta log listrik.
- **Arsitektur Service Layer:** Perhitungan emisi dipisahkan ke dalam `EmissionCalculatorService` untuk akurasi dan kemudahan pemeliharaan.
- **Output JSON Konsisten:** Semua respon API disajikan dalam format JSON standar untuk konsumsi aplikasi frontend.

---

## 🛠️ Tech Stack & Dependencies

- **Framework:** Laravel 11.x
- **Bahasa:** PHP 8.2+
- **Database:** MySQL
- **Autentikasi:** Laravel Sanctum (Token-Based Authentication)

---

## 📐 Arsitektur Database & Relasi

Relasi utama pada sistem:

- `users` ➡️ `transport_logs` (1:N)
- `users` ➡️ `electricity_logs` (1:N)
- `transport_types` ➡️ `transport_logs` (1:N)
- `recommendations` ➡️ `user_recommendations` (1:N)
- `users` ➡️ `user_recommendations` (1:N)

---

## 💻 Panduan Instalasi Lokal

Ikuti langkah berikut untuk menjalankan EcoTrack Backend:

### 1. Clone proyek dan masuk ke direktori
```bash
cd ecotrack-backend
```

### 2. Install dependensi Composer
```bash
composer install
```

### 3. Konfigurasi file environment
Salin `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Buka `.env` dan sesuaikan pengaturan database:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecotrack_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Jalankan migrasi dan seeder
```bash
php artisan migrate --seed
```

### 6. Jalankan server lokal
```bash
php artisan serve
```

API akan tersedia di: `http://127.0.0.1:8000`

---

## 📘 Dokumentasi Endpoint API

Semua request harus menyertakan header berikut:

```http
Accept: application/json
Content-Type: application/json
```

### 🔓 Endpoint Publik

#### 1. Registrasi pengguna baru
- URL: `/api/register`
- Method: `POST`
- Body:

```json
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "password123"
}
```

#### 2. Login pengguna
- URL: `/api/login`
- Method: `POST`
- Body:

```json
{
  "email": "johndoe@example.com",
  "password": "password123"
}
```

### 🔒 Endpoint Terproteksi

> Header: `Authorization: Bearer <token_anda>`

#### 3. Cek profil pengguna
- URL: `/api/user/profile`
- Method: `GET`

#### 4. Catat log transportasi
- URL: `/api/transport-logs`
- Method: `POST`
- Body:

```json
{
  "transport_type_id": 1,
  "distance_km": 12.5,
  "activity_date": "2026-06-11"
}
```

> Catatan: `transport_type_id` 1 = Mobil Bensin, 2 = Sepeda Motor, dst sesuai seeder.

#### 5. Lihat riwayat transportasi
- URL: `/api/transport-logs`
- Method: `GET`

#### 6. Catat log listrik
- URL: `/api/electricity-logs`
- Method: `POST`
- Body:

```json
{
  "usage_kwh": 150.00,
  "period_month": "2026-06",
  "record_date": "2026-06-11"
}
```

#### 7. Lihat riwayat listrik
- URL: `/api/electricity-logs`
- Method: `GET`

#### 8. Update log transportasi
- URL: `/api/transport-logs/{id}`
- Method: `PUT` atau `PATCH`

#### 9. Hapus log transportasi
- URL: `/api/transport-logs/{id}`
- Method: `DELETE`

#### 10. Update log listrik
- URL: `/api/electricity-logs/{id}`
- Method: `PUT` atau `PATCH`

#### 11. Hapus log listrik
- URL: `/api/electricity-logs/{id}`
- Method: `DELETE`

#### 12. Logout
- URL: `/api/logout`
- Method: `POST`

---

## � Deployment & Maintenance (EcoTrack)

### 1. Deployment Backend Laravel

#### Persyaratan Server
- PHP 8.2+
- Composer
- MySQL 5.7+ / PostgreSQL 10+
- Laravel 11.x
- Apache atau Nginx
- SSL untuk produksi

#### Langkah Deploy
```bash
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Jalankan Produksi
- Development: `php artisan serve --host=0.0.0.0 --port=8000`
- Production: gunakan virtual host Apache/Nginx dengan document root ke `public/`

#### Checklist Produksi
- Set `APP_ENV=production` dan `APP_DEBUG=false`
- Gunakan SSL
- Atur permission folder `storage/` dan `bootstrap/cache/`
- Aktifkan logging harian untuk debugging

### 2. Deployment Frontend Flutter

#### Persyaratan
- Flutter SDK terbaru
- Android Studio / VS Code Flutter extension
- Backend Laravel berjalan di `http://127.0.0.1:8000/api` atau domain produksi

#### Langkah Deploy
```bash
flutter pub get
flutter build apk --release
flutter build appbundle --release
```

#### Konfigurasi Base URL
- Emulator Android: `http://10.0.2.2:8000/api`
- Emulator iOS: `http://localhost:8000/api`
- Device fisik: `http://192.168.x.x:8000/api`
- Produksi: `https://your-domain/api`

### 3. Maintenance & Monitoring

#### Monitoring
- Cek log backend di `storage/logs/laravel.log`
- Pantau error 4xx/5xx dari API
- Pastikan koneksi ke database stabil

#### Backup Database
```bash
mysqldump -u root -p ecotrack_db > backup_$(date +%Y%m%d).sql
```

#### Update Berkala
```bash
composer update --no-dev
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
flutter pub upgrade
flutter analyze
```

#### Keamanan
- Rotasi token dan password secara berkala
- Jalankan `composer audit`
- Gunakan SSL dan file permission yang benar

---

## �📝 Catatan Tambahan

- Pastikan dokumentasi Postman diekspor dalam format `.json` jika diperlukan untuk lampiran.
- Struktur folder mengikuti standar Laravel dan prinsip separation of concerns via service layer.

---

README ini dirancang agar penguji atau juri kompetisi dapat memahami alur kerja backend dengan cepat.
