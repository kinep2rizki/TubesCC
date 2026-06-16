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
