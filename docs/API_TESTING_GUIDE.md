# Panduan Pengujian API EcoTrack dengan Insomnia

File ini menjelaskan cara melakukan pengujian API Backend EcoTrack secara terstruktur menggunakan **Insomnia**. Panduan dibagi menjadi tiga tahapan utama yang sesuai dengan *Insomnia Collection* yang telah disediakan.

---

## Tahap 1: Impor Collection
1. Buka aplikasi **Insomnia**.
2. Klik **Create** → **Import / Export** → **Import File**.
3. Pilih file `Insomnia_Collection.json` yang berada di folder `docs`.
4. Setelah sukses, akan muncul workspace baru bernama **EcoTrack API** dengan tiga folder modul: **Auth**, **Transport**, **Electricity**.

---

## Tahap 2: Konfigurasi Environment & Token
1. Di pojok kiri atas, pilih dropdown **Environment** (biasanya "Base Environment").
2. Pastikan variabel `base_url` mengarah ke server lokal Anda, contohnya:
   ```
   http://127.0.0.1:8000/api
   ```
3. Variabel `token` masih berisi placeholder `GANTI_DENGAN_TOKEN_ANDA`. Kita akan menggantinya setelah login atau register.

### Cara Mendapatkan Token
| Langkah | Aksi |
|---|---|
| a | Pilih request **Auth → Register** atau **Auth → Login**. |
| b | Klik **Send**. |
| c | Pada respons, salin nilai `access_token` (contoh: `1|abcd...`). |
| d | Kembali ke **Environment**, tempel nilai token ke field `token`. |

Setelah token terisi, semua request yang memerlukan otorisasi otomatis menyertakan header `Authorization: Bearer <token>`.

---

## Tahap 3: Pengujian Modul
### 📁 Auth
- **POST `/register`** – Registrasi pengguna baru.
- **POST `/login`** – Mendapatkan token akses.
- **GET `/user/profile`** – Mengambil data profil (memerlukan token).

### 📁 Transport
1. **GET `/transport-types`** – Menampilkan daftar jenis kendaraan (id & nama).
2. **POST `/transport-logs`** – Menambahkan log aktivitas transportasi.
   - *Body* (JSON):
     ```json
     {
       "transport_type_id": 1,
       "distance_km": 15.5,
       "activity_date": "2026-06-14"
     }
     ```
   - *Response* berisi `emission_kg` yang dihitung otomatis.
3. **GET `/transport-logs`** – Menampilkan seluruh riwayat transportasi lengkap dengan nilai emisi.

### 📁 Electricity
1. **POST `/electricity-logs`** – Menambahkan log pemakaian listrik.
   - *Body* (JSON):
     ```json
     {
       "usage_kwh": 150.5,
       "period_month": "2026-06",
       "record_date": "2026-06-14"
     }
     ```
   - *Response* berisi `emission_kg`.
2. **GET `/electricity-logs`** – Menampilkan semua riwayat pemakaian listrik.

---

## Tips Tambahan
- **Urutan Pengujian**: Mulai dari **Auth** untuk mendapatkan token, lalu **Transport** dan **Electricity**.
- **Validasi**: Pastikan setiap response mengandung field `emission_kg` dan data yang Anda kirimkan.
- **Debug**: Jika request gagal, periksa log server Laravel (`storage/logs/laravel.log`).

---

*Gunakan panduan ini setiap kali Anda melakukan perubahan atau perbaikan pada API backend.*
