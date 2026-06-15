# EcoTrack Backend API Documentation

## Overview
EcoTrack Backend saat ini menyediakan API untuk:
- autentikasi token berbasis Laravel Sanctum
- profil pengguna
- pencatatan dan riwayat transportasi
- pencatatan dan riwayat penggunaan listrik

> Catatan: Saat ini route publik `/api/register` dan `/api/login` sudah tersedia, sedangkan endpoint riwayat data dilindungi oleh `auth:sanctum`.

## Base URL
Gunakan base URL backend:
- `http://127.0.0.1:8000`

Semua endpoint API terletak di bawah prefix:
- `/api`

## Autentikasi
Backend menggunakan `Laravel Sanctum` untuk autentikasi token.

Header yang wajib dikirim untuk endpoint dilindungi:
```http
Authorization: Bearer <access_token>
Accept: application/json
Content-Type: application/json
```

### CSRF / Sanctum
Backend juga mendaftarkan route `GET /sanctum/csrf-cookie` untuk kebutuhan Sanctum SPAs, tetapi untuk Flutter mobile app cukup gunakan token bearer.

## Endpoint Terdaftar

### 1. POST /api/register
- Deskripsi: Registrasi pengguna baru.
- Middleware: publik.
- Body:
```json
{
  "name": "John Doe",
  "email": "johndoe@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 2. POST /api/login
- Deskripsi: Login pengguna dan dapatkan token Sanctum.
- Middleware: publik.
- Body:
```json
{
  "email": "johndoe@example.com",
  "password": "password123"
}
```

### 3. GET /api/user
- Deskripsi: Mengambil data user saat ini berdasarkan token autentikasi.
- Middleware: `auth:sanctum`
- Response contoh:
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "johndoe@example.com",
  "email_verified_at": null,
  "created_at": "2026-06-11T...",
  "updated_at": "2026-06-11T..."
}
```

### 4. GET /api/user/profile
- Deskripsi: Mengambil profil pengguna.
- Middleware: `auth:sanctum`
- Response contoh:
```json
{
  "status": "success",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "johndoe@example.com"
  }
}
```

### 5. POST /api/logout
- Deskripsi: Mencabut token akses saat ini.
- Middleware: `auth:sanctum`
- Body: kosong
- Response contoh:
```json
{
  "message": "Berhasil logout, token akses telah dicabut."
}
```

### 6. GET /api/transport-logs
- Deskripsi: Mengambil riwayat log transportasi user.
- Middleware: `auth:sanctum`
- Response contoh:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "transport_type_id": 1,
      "distance_km": 12.5,
      "emission_kg": 3.75,
      "activity_date": "2026-06-11",
      "created_at": "2026-06-11T...",
      "updated_at": "2026-06-11T...",
      "transport_type": {
        "id": 1,
        "name": "Mobil Bensin",
        "emission_factor": 0.3
      }
    }
  ]
}
```

### 7. POST /api/transport-logs
- Deskripsi: Menyimpan log transportasi baru dan menghitung emisi otomatis.
- Middleware: `auth:sanctum`
- Body:
```json
{
  "transport_type_id": 1,
  "distance_km": 12.5,
  "activity_date": "2026-06-11"
}
```
- Response contoh:
```json
{
  "message": "Aktivitas transportasi berhasil dicatat!",
  "data": {
    "id": 2,
    "user_id": 1,
    "transport_type_id": 1,
    "distance_km": 12.5,
    "emission_kg": 3.75,
    "activity_date": "2026-06-11",
    "transport_type": {
      "id": 1,
      "name": "Mobil Bensin",
      "emission_factor": 0.3
    }
  }
}
```

### 8. PUT/PATCH /api/transport-logs/{id}
- Deskripsi: Memperbarui log transportasi milik user yang sedang login.
- Middleware: `auth:sanctum`
- Body contoh:
```json
{
  "transport_type_id": 2,
  "distance_km": 8.5,
  "activity_date": "2026-06-15"
}
```

### 9. DELETE /api/transport-logs/{id}
- Deskripsi: Menghapus log transportasi milik user yang sedang login.
- Middleware: `auth:sanctum`

### 10. GET /api/electricity-logs
- Deskripsi: Mengambil riwayat penggunaan listrik user.
- Middleware: `auth:sanctum`
- Response contoh:
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "usage_kwh": 150.0,
      "period_month": "2026-06",
      "emission_kg": 130.5,
      "record_date": "2026-06-11",
      "created_at": "2026-06-11T...",
      "updated_at": "2026-06-11T..."
    }
  ]
}
```

### 11. POST /api/electricity-logs
- Deskripsi: Menyimpan log listrik baru dan menghitung emisi otomatis.
- Middleware: `auth:sanctum`
- Body:
```json
{
  "usage_kwh": 150.00,
  "period_month": "2026-06",
  "record_date": "2026-06-11"
}
```
- Response contoh:
```json
{
  "message": "Penggunaan listrik berhasil dicatat!",
  "data": {
    "id": 3,
    "user_id": 1,
    "usage_kwh": 150.0,
    "period_month": "2026-06",
    "emission_kg": 130.5,
    "record_date": "2026-06-11",
    "created_at": "2026-06-11T...",
    "updated_at": "2026-06-11T..."
  }
}
```

### 12. PUT/PATCH /api/electricity-logs/{id}
- Deskripsi: Memperbarui log listrik milik user yang sedang login.
- Middleware: `auth:sanctum`
- Body contoh:
```json
{
  "usage_kwh": 180.5,
  "period_month": "2026-07",
  "record_date": "2026-06-16"
}
```

### 13. DELETE /api/electricity-logs/{id}
- Deskripsi: Menghapus log listrik milik user yang sedang login.
- Middleware: `auth:sanctum`

## Catatan Penting untuk Flutter
- Backend sudah menyediakan route publik `/api/register` dan `/api/login`.
- Token Sanctum dapat diperoleh dari endpoint login lalu digunakan untuk semua endpoint yang dilindungi.
- Semua endpoint data penggunanya dilindungi oleh `auth:sanctum`.
- Pastikan request header mencakup `Authorization: Bearer <token>`.

## Struktur Layanan Backend
- `App\Http\Controllers\Auth\AuthController`
  - `profile()`
  - `logout()`
- `App\Http\Controllers\TransportLogController`
  - `index()`
  - `store()`
  - `update()`
  - `destroy()`
- `App\Http\Controllers\ElectricityLogController`
  - `index()`
  - `store()`
  - `update()`
  - `destroy()`
- `App\Services\Auth\AuthService`
  - `register()`
  - `login()`
  - `logout()`
- `App\Services\TransportService`
  - `getUserLogs()`
  - `createLog()`
  - `updateLog()`
  - `deleteLog()`
- `App\Services\ElectricityService`
  - `getUserLogs()`
  - `createLog()`
  - `updateLog()`
  - `deleteLog()`

## Routes tambahan sistem
- `GET /sanctum/csrf-cookie`
- `GET /storage/{path}`
- `GET /up`
