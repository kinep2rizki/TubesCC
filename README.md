# PETA Frontend (Tubes CC)

Repositori ini adalah **Pure Frontend Layer** untuk aplikasi PETA, dibangun menggunakan Laravel Blade, Alpine.js, dan TailwindCSS.
Layanan ini berjalan di Port `8000` (atau via Vite) dan bertugas murni sebagai *User Interface* (UI).

> [!WARNING]
> **TIDAK ADA DATABASE ATAU MODEL DI SINI!**
> Sesuai arsitektur Microservices, repository ini tidak menyimpan data ke database secara langsung.
> Seluruh `Model` dan `Migrations` telah dihapus.

## 🔗 Ketergantungan Layanan (Dependencies)

Untuk dapat menjalankan aplikasi ini secara penuh, Anda harus menjalankan 2 layanan Backend lainnya di background:
1. **Auth Service (`TubesCC_BackendJWT`)** di Port `8001`. (Untuk Login & Register).
2. **Project Service (`TubesCC_ProjectService`)** di Port `8002`. (Untuk mengambil data Komunitas, Event, dsb).

---

## 🏛️ Arsitektur Microservices
Aplikasi ini (Port `8000`) bertindak sebagai **API Gateway / Frontend Layer**. Karena ini murni aplikasi Frontend berbasis Blade, ia tidak memiliki koneksi ke database bisnis utama (model telah dihapus).
- **Frontend Layer (Port `8000`)**: Bertugas merender User Interface (Blade) dan meneruskan interaksi pengguna ke backend.
- **Auth Service (Port `8001`)**: Menangani proses autentikasi (Login/Register) dan mengembalikan token JWT.
- **Project Service (Port `8002`)**: Menangani proses bisnis (Komunitas, Event, User, Presensi, Sertifikat).

*Alur Autentikasi:*
Saat pengguna melakukan login, Frontend akan meneruskan request ke Auth Service. Token JWT yang dikembalikan kemudian disimpan di Session Frontend melalui route internal `/sync-session`. Setiap kali Frontend membutuhkan data dari Project Service, ia akan melampirkan token JWT dari session tersebut.

---

## 🌐 Daftar Endpoint (Web Routes)
Karena ini adalah **Frontend Layer**, endpoint yang diekspos utamanya adalah Web Routes yang mengembalikan file View (HTML) atau memproses state UI, bukan merespons dengan format JSON murni layaknya backend API biasa.

| Method | Endpoint | Deskripsi | Respons / Action |
|--------|----------|-----------|------------------|
| **GET** | `/login` | Halaman Login | View halaman login |
| **POST** | `/sync-session` | Sinkronisasi Token | Menyimpan token JWT ke Session Frontend |
| **POST** | `/logout` | Logout | Hapus Session & Redirect |
| **GET** | `/` | Dashboard / Overview Event | View Dashboard utama |
| **GET** | `/communities` | Daftar Komunitas | View Manajemen Komunitas |
| **GET** | `/users` | Manajemen User | View Manajemen Pengguna |
| **PUT** | `/users/{id}/role` | Update Role User | Update status role (Action AJAX) |
| **GET** | `/events` | Manajemen Event | View daftar dan kelola Event |
| **GET** | `/events/{id}` | Detail Event | View spesifik detail dari Event |
| **POST** | `/events` | Buat Event Baru | Submit pembuatan Event |
| **PUT** | `/events/{id}` | Update Event | Submit perubahan Event |
| **GET** | `/events/{eventId}/participants` | Daftar Peserta Event | View daftar partisipan |
| **POST** | `/events/{eventId}/participants` | Tambah Peserta | Action menambah peserta |
| **PUT** | `/events/{eventId}/participants/{participantId}`| Update Peserta | Action mengubah data peserta |
| **GET** | `/events/{eventId}/participants/export`| Export Data Peserta | Download file Excel/CSV |
| **GET** | `/events/{eventId}/attendance` | Halaman Presensi | View halaman kehadiran (Attendance) |
| **POST** | `/events/{eventId}/attendance` | Submit Presensi | Action submit absensi partisipan |
| **GET** | `/events/{eventId}/certificates` | Manajemen Sertifikat | View daftar/kelola sertifikat |
| **GET** | `/analytics` | Halaman Analytics | View grafik dan statistik platform |
| **GET** | `/analytics/export` | Export Analytics | Download laporan performa / analitik |
| **GET** | `/settings` | Pengaturan Profil | View halaman profil pengguna |
| **PUT** | `/settings/profile` | Update Profil | Action pembaruan informasi profil |

---

## 📄 Postman Collection / API Docs
Karena repositori ini adalah murni **Frontend**, tidak ada Postman Collection khusus untuk folder ini. Jika Anda ingin melakukan testing API via Postman atau melihat API Docs, silakan mengarah ke masing-masing repository Backend:
- **[Postman/API Docs Auth Service]** -> Rujuk ke repository `TubesCC_BackendJWT`
- **[Postman/API Docs Project Service]** -> Rujuk ke repository `TubesCC_ProjectService`

*Catatan: Route internal frontend seperti `/sync-session` dapat dites, namun pada dasarnya ia hanya menerima payload yang berisi JWT lalu menyimpannya ke Cookie/Session.*

## 🚧 Status & TODO (Penting untuk AI/Developer Selanjutnya)

Karena pemisahan *Frontend* dari *Backend* baru saja dilakukan, **Sebagian besar halaman UI saat ini akan mengalami ERROR (Crash 500)**.
Hal ini disebabkan karena *Controller* yang ada di repositori ini (seperti `CommunityController` atau `EventController`) masih menggunakan kode lama yang mencoba memanggil *Eloquent Model* (misal: `Community::all()`) yang sudah dihapus.

### Langkah Integrasi Berikutnya:
1. **Refactor Controllers**:
   Ubah semua *Controller* di frontend ini agar tidak lagi memanggil *Database/Model*. Gantilah dengan pemanggilan HTTP Request (Guzzle/Axios) ke API backend.
   
   *Contoh sebelum:*
   ```php
   public function index() {
       $communities = Community::all();
       return view('pages.community', compact('communities'));
   }
   ```
   *Contoh sesudah:*
   ```php
   use Illuminate\Support\Facades\Http;

   public function index() {
       // Panggil API Project Service
       $response = Http::get('http://127.0.0.1:8002/api/communities');
       $communities = $response->json()['data'];
       
       return view('pages.community', compact('communities'));
   }
   ```

2. **Penyimpanan Token JWT (Session/Cookie)**:
   Saat user login dengan memanggil Auth Service (Port 8001), token JWT yang dikembalikan harus disimpan di sesi (Session/Cookie) Frontend Laravel ini.

3. **Injeksi Token ke setiap Request**:
   Setiap kali Frontend melakukan `Http::get` ke Project Service (Port 8002) yang membutuhkan login, pastikan Anda melampirkan token JWT dari sesi tersebut:
   `Http::withToken(session('jwt_token'))->get(...)`

---

## ⚙️ Environment Variables (.env)
File `.env` di Frontend ini digunakan untuk konfigurasi dasar aplikasi dan juga service eksternal (seperti WebSockets/Reverb). Pastikan Anda menduplikat file `.env.example` menjadi `.env` dan mengisi beberapa value utama berikut:

- `APP_URL=http://localhost:8000` (Atau sesuaikan jika environment berbeda)
- **Database (Bila Ada Log/Queue Lokal):** Meskipun tidak ada database bisnis utama, konfigurasi DB seperti `DB_CONNECTION`, `DB_HOST`, dll tetap ada jika Frontend menggunakan Database untuk session, cache, atau logs lokal. Sesuaikan `DB_PASSWORD` sesuai mesin Anda (misal `postgres`/`root`).
- **JWT Configurations:**
  - `JWT_SECRET` : Samakan dengan secret key yang ada di Auth Service jika diperlukan untuk memvalidasi/decode token secara lokal di sisi Frontend (meski validasi utamanya tetap ada di Backend).
- **Reverb / WebSockets:** 
  - `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET` : Isi dengan kredensial WebSocket untuk komunikasi real-time antar layanan.
  - `VITE_REVERB_*` : Konfigurasi ini diteruskan secara otomatis ke Vite untuk digunakan oleh file JavaScript/Alpine di Frontend.

*Catatan: Credentials rahasia di `.env.example` sudah dikosongkan (tanpa credential asli) untuk menjaga keamanan repository.*

---

---

## ⚙️ Environment Variables (.env)
File `.env` di Frontend ini digunakan untuk konfigurasi dasar aplikasi dan juga service eksternal (seperti WebSockets/Reverb). Pastikan Anda menduplikat file `.env.example` menjadi `.env` dan mengisi beberapa value utama berikut:

- `APP_URL=http://localhost:8000` (Atau sesuaikan jika environment berbeda)
- **Database (Bila Ada Log/Queue Lokal):** Meskipun tidak ada database bisnis utama, konfigurasi DB seperti `DB_CONNECTION`, `DB_HOST`, dll tetap ada jika Frontend menggunakan Database untuk session, cache, atau logs lokal. Sesuaikan `DB_PASSWORD` sesuai mesin Anda (misal `postgres`/`root`).
- **JWT Configurations:**
  - `JWT_SECRET` : Samakan dengan secret key yang ada di Auth Service jika diperlukan untuk memvalidasi/decode token secara lokal di sisi Frontend (meski validasi utamanya tetap ada di Backend).
- **Reverb / WebSockets:** 
  - `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET` : Isi dengan kredensial WebSocket untuk komunikasi real-time antar layanan.
  - `VITE_REVERB_*` : Konfigurasi ini diteruskan secara otomatis ke Vite untuk digunakan oleh file JavaScript/Alpine di Frontend.

*Catatan: Credentials rahasia di `.env.example` sudah dikosongkan (tanpa credential asli) untuk menjaga keamanan repository.*

---

## 💻 Cara Menjalankan Frontend

```bash
# 1. Clone repository
git clone https://github.com/kinep2rizki/TubesCC.git
cd TubesCC

# 2. Install Dependency PHP & Node
composer install
npm install

# 3. Setup Environment
cp .env.example .env
php artisan key:generate

# 4. Jalankan Laravel & Vite
php artisan serve --port=8000
npm run dev
```
