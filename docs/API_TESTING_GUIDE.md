# Panduan Testing API EcoTrack dengan Insomnia

File ini menjelaskan cara melakukan pengujian API Backend EcoTrack menggunakan aplikasi **Insomnia**.

## 1. Import Collection
1. Buka aplikasi **Insomnia**.
2. Klik tombol **Create** (atau **Import / Export** di pojok kanan atas).
3. Pilih **File** dan cari file `Insomnia_Collection.json` yang ada di dalam folder `docs` ini.
4. Akan muncul Workspace baru bernama **EcoTrack API**.

## 2. Setting Environment & Token
Sistem API kita menggunakan *Bearer Token* (Sanctum) untuk proteksi endpoint.
1. Klik dropdown environment di bagian atas kiri Insomnia (secara default bertuliskan "Base Environment").
2. Pastikan variabel `base_url` sudah mengarah ke lokal server Anda: `http://127.0.0.1:8000/api`.
3. Variabel `token` masih tertulis "GANTI_DENGAN_TOKEN_ANDA". 

**Cara mendapatkan Token:**
1. Buka folder **Auth** di Insomnia, lalu pilih request **Register** atau **Login**.
2. Jalankan request tersebut (tekan tombol Send).
3. Jika berhasil, Anda akan mendapatkan Response berupa JSON yang berisi `"access_token"`.
4. *Copy* isi token tersebut (teks panjang yang berawalan `1|...`).
5. Kembali ke pengaturan *Environment*, dan *paste* di sebelah `"token"`.
6. Kini semua endpoint yang lain (seperti Add Transport, Get Profile, dsb) sudah otomatis menggunakan token tersebut.

---

## 3. Detail Modul dan API
Di dalam workspace Anda, sudah tersedia folder untuk masing-masing modul:

### 📁 Auth
- **POST `/register`** : Mendaftarkan akun pengguna baru.
- **POST `/login`** : Mendapatkan token akses.
- **GET `/user/profile`** : Mengambil detail data user (butuh token).

### 📁 Transport
- **POST `/transport-logs`** : Menambahkan log aktivitas transportasi.
  - *Body Required:*
    - `transport_type_id`: ID kendaraan (contoh: 1 = Mobil Bensin, 2 = Mobil Listrik, 3 = Motor).
    - `distance_km`: Jarak tempuh (angka desimal).
    - `activity_date`: Tanggal (format YYYY-MM-DD).
  - *Response:* Sistem akan otomatis mengembalikan total `emission_kg` dari hasil kalkulasi.
- **GET `/transport-logs`** : Menampilkan seluruh histori aktivitas transportasi beserta nilai emisi dan nama kendaraannya.

### 📁 Electricity
- **POST `/electricity-logs`** : Menambahkan log penggunaan listrik.
  - *Body Required:*
    - `usage_kwh`: Pemakaian listrik (angka desimal).
    - `period_month`: Bulan pencatatan (format YYYY-MM, e.g., "2026-06").
    - `record_date`: Tanggal catat (format YYYY-MM-DD).
  - *Response:* Sistem otomatis mengkalkulasi emisi karbonnya ke dalam `emission_kg`.
- **GET `/electricity-logs`** : Menampilkan seluruh histori pemakaian listrik.

---
*Gunakan panduan ini setiap kali Anda melakukan modifikasi atau perbaikan (bug fixing) pada fitur Backend.*
