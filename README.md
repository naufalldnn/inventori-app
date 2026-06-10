# Inventori Laravel 12

Aplikasi inventori berbasis Laravel 12, PostgreSQL Neon, Blade, Tailwind CSS, Laravel Reverb, Docker, dan Traefik.

## ✅ Setup Status

✅ File `.env` sudah dikonfigurasi dengan Neon credentials
✅ Docker images sudah di-build
✅ Semua containers sudah running
✅ Database migrations sudah di-apply

## Menjalankan Aplikasi

### 1. Memastikan Containers Berjalan

```bash
docker compose up -d
```

### 2. Cek Status Containers

```bash
docker ps
```

Pastikan keempat container berjalan:
- `inventori_app` - Aplikasi Laravel
- `inventori_queue` - Queue worker
- `inventori_reverb` - WebSocket server
- `inventori_traefik` - Reverse proxy

### 3. Akses Aplikasi

- **Aplikasi**: http://inventori.localhost
- **Traefik Dashboard**: http://localhost:8080/dashboard/
- **WebSocket**: ws://localhost:6001

## Perintah Umum

### Menjalankan Artisan Command

```bash
docker compose exec inventori_app php artisan {command}
```

Contoh:
```bash
docker compose exec inventori_app php artisan tinker
docker compose exec inventori_app php artisan migrate:refresh --seed
docker compose exec inventori_app php artisan queue:work
```

### Melihat Logs

```bash
# Melihat logs semua service
docker compose logs -f

# Melihat logs app saja
docker logs -f inventori_app

# Melihat logs reverb (websocket)
docker logs -f inventori_reverb
```

### Stop/Start Containers

```bash
# Stop semua containers
docker compose down

# Restart containers
docker compose restart
```

## Akun Seeder

- Admin: `admin@example.com` / `password`
- Petugas: `petugas@example.com` / `password`

## Database

Database menggunakan PostgreSQL Neon dengan konfigurasi di `.env`:
- Host: `ep-square-boat-aot48xbh-pooler.c-2.ap-southeast-1.aws.neon.tech`
- Database: `neondb`
- Username: `neondb_owner`
- Password: Tersimpan di `.env`

## Catatan Penting

- **Jangan commit `.env`** ke repository (sudah ada di `.gitignore`)
- Credential database sudah dikonfigurasi, jangan hardcode di code
- Untuk development lokal, gunakan `docker compose exec` untuk menjalankan commands
- Reverb berjalan di port `6001` untuk fitur real-time
- Traefik menghandle routing dengan domain `inventori.localhost`

