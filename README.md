# RUANGISASI — Sistem Pemesanan Jasa

## Fitur utama
- User membuat pemesanan jasa.
- Nama jasa, alamat, tanggal selesai, dan budget.
- Tanggal mulai otomatis menggunakan tanggal saat pemesanan dibuat.
- Alamat dapat diambil otomatis dari lokasi browser melalui reverse geocoding OpenStreetMap/Nominatim.
- Status persetujuan: Menunggu, Setuju, Tidak Setuju.
- Status proses: Menunggu, Pengerjaan, Perbaikan, Selesai, Ditolak.
- Keputusan Setuju/Tidak Setuju hanya dapat dilakukan Super Admin.
- Admin hanya mengubah status proses pengerjaan setelah pesanan disetujui.
- User dapat melihat riwayat dan status pemesanannya.

## Role demo setelah seeding

| Role | Email | Password |
|---|---|---|
| Super Admin | superadmin@ruangisasi.test | password |
| Admin | admin@ruangisasi.test | password |
| User | user@ruangisasi.test | password |

## Menjalankan project

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Pastikan `.env` mengarah ke database MySQL/MariaDB yang digunakan di komputer.
