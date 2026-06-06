# Test IT Development - POS System

Sistem Point of Sale (POS) sederhana yang dibangun menggunakan Laravel 11. 
Dilengkapi dengan fitur *auto-generate invoice*, *pessimistic locking* (anti *race-condition*), validasi alfanumerik, dan arsitektur MVC yang solid.

## Persyaratan Sistem
- PHP >= 8.2
- MySQL / MariaDB
- Composer

## Instalasi
1. Clone repository ini: `git clone [URL_REPO_KAMU]`
2. Jalankan `composer install`
3. Salin `.env.example` menjadi `.env` dan sesuaikan konfigurasi database.
4. Jalankan `php artisan key:generate`
5. Jalankan migrasi database: `php artisan migrate`
6. (Opsional) Jalankan local server: `php artisan serve`

## Fitur Utama
- CRUD Master Produk & Customer (dengan proteksi *foreign key restrict*).
- Form Transaksi dinamis (Master-Detail) dengan kalkulasi diskon bertingkat.
- Pengurangan stok otomatis berbasis `DB::transaction()` untuk integritas data.
- Halaman cetak *invoice*.
