# Inventori Laravel 12

Aplikasi inventori berbasis Laravel 12 untuk mengelola kategori, barang, stok masuk/keluar, laporan PDF, checkout pembayaran, tracking pesanan, notifikasi, dan chat internal real-time.

## Tech Stack

- Laravel 12 dan PHP 8.2
- PostgreSQL, siap dipakai dengan Neon
- Blade, Tailwind CSS, Vite
- Laravel Reverb untuk WebSocket/chat real-time
- Laravel DomPDF untuk laporan PDF
- Docker Compose dan Traefik
- Integrasi opsional: Google OAuth, Doku, Cloudinary, Binderbyte

## Fitur Utama

- Autentikasi email/password dan login Google
- Role `admin`, `petugas`, dan `user`
- Manajemen kategori dan item inventori
- Stok masuk dan stok keluar
- Dashboard ringkasan inventori
- Laporan stok dan transaksi dalam format halaman web/PDF
- Checkout item dengan integrasi Doku jika credential tersedia
- Tracking pesanan dengan Binderbyte jika API key tersedia
- Notifikasi stok dan chat internal berbasis Reverb

## Prasyarat

- Docker dan Docker Compose
- Git
- Port lokal `80`, `8080`, dan `6001` tersedia

Untuk menjalankan tanpa Docker, gunakan PHP 8.2+, Composer, Node.js, NPM, dan PostgreSQL.

## Setup Dengan Docker

1. Salin file environment:

   ```bash
   cp .env.example .env
   ```

2. Isi konfigurasi database PostgreSQL di `.env`.

   Minimal isi:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=
   DB_PORT=5432
   DB_DATABASE=
   DB_USERNAME=
   DB_PASSWORD=
   DB_SSLMODE=require
   ```

3. Jalankan container:

   ```bash
   docker compose up -d --build
   ```

4. Generate application key:

   ```bash
   docker compose exec app php artisan key:generate
   ```

5. Jalankan migration dan seeder:

   ```bash
   docker compose exec app php artisan migrate --seed
   ```

6. Akses aplikasi:

   - Aplikasi: http://inventori.localhost
   - Alternatif lokal: http://localhost
   - Traefik dashboard: http://localhost:8080/dashboard/
   - Reverb/WebSocket: ws://localhost:6001

## Service Docker

Docker Compose menjalankan service berikut:

- `app`: aplikasi Laravel
- `queue`: queue worker Laravel
- `reverb`: WebSocket server Laravel Reverb
- `traefik`: reverse proxy untuk akses HTTP lokal

Nama container yang dibuat:

- `inventori_app`
- `inventori_queue`
- `inventori_reverb`
- `inventori_traefik`

## Command Harian

Menjalankan Artisan:

```bash
docker compose exec app php artisan {command}
```

Contoh:

```bash
docker compose exec app php artisan route:list
docker compose exec app php artisan migrate
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan tinker
```

Melihat log:

```bash
docker compose logs -f
docker compose logs -f app
docker compose logs -f queue
docker compose logs -f reverb
```

Restart atau stop container:

```bash
docker compose restart
docker compose down
```

Build asset frontend:

```bash
docker compose exec app npm run build
```

Untuk mode development Vite:

```bash
docker compose exec app npm run dev
```

## Akun Seeder

Setelah menjalankan `php artisan migrate --seed`, tersedia akun awal:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Petugas | `petugas@example.com` | `password` |
| User | `user@example.com` | `password` |

## Konfigurasi Opsional

### Google OAuth

Isi credential berikut di `.env` untuk mengaktifkan login Google:

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

Jika memakai domain lokal utama, redirect URI dapat disesuaikan menjadi:

```env
GOOGLE_REDIRECT_URI=http://inventori.localhost/auth/google/callback
```

### Doku

Checkout online akan aktif jika credential Doku lengkap:

```env
DOKU_CLIENT_ID=
DOKU_MALL_ID=
DOKU_SHARED_KEY=
DOKU_SANDBOX_URL=https://sandbox.doku.com
DOKU_API_URL=https://api-sandbox.doku.com
DOKU_NOTIFICATION_URL=
```

Callback publik aplikasi:

```text
/doku/callback
```

Return URL aplikasi:

```text
/doku/return
```

### Cloudinary

Isi konfigurasi berikut jika upload media item memakai Cloudinary:

```env
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_FOLDER=inventori/items
```

### Binderbyte

Isi API key jika tracking kurir ingin memakai Binderbyte:

```env
BINDERBYTE_API_KEY=
BINDERBYTE_BASE_URL=http://api.binderbyte.com/v1
BINDERBYTE_COURIER=jnt
```

## Struktur Penting

- `app/Http/Controllers`: controller halaman dan proses bisnis
- `app/Models`: model Eloquent
- `app/Services`: service integrasi eksternal
- `database/migrations`: skema database
- `database/seeders`: data awal
- `resources/views`: tampilan Blade
- `routes/web.php`: route web aplikasi

## Catatan

- Jangan commit `.env` karena berisi credential.
- Gunakan `.env.example` sebagai template environment baru.
- Jalankan command Compose dengan nama service, misalnya `app`, bukan nama container `inventori_app`.
- Reverb berjalan di port `6001`.
- Traefik menangani routing untuk `inventori.localhost` dan `localhost`.
