# Sistem Rekomendasi Saham TOPSIS

Aplikasi Sistem Pendukung Keputusan (SPK) untuk rekomendasi saham menggunakan metode **TOPSIS** (Technique for Order of Preference by Similarity to Ideal Solution).

## 📋 Deskripsi

Aplikasi ini membantu investor dalam mengambil keputusan investasi saham berdasarkan kriteria yang objektif menggunakan metode TOPSIS. Metode ini menghitung kedekatan relatif setiap alternatif terhadap solusi ideal untuk menghasilkan ranking saham terbaik.

## 🚀 Fitur

- **Manajemen Data Saham**: CRUD (Create, Read, Update, Delete) data saham
- **Perhitungan TOPSIS**: Implementasi algoritma TOPSIS lengkap
- **Hasil & Rekomendasi**: Menampilkan ranking saham berdasarkan perhitungan
- **Dashboard Interaktif**: Visualisasi data dan hasil analisis
- **Responsive Design**: Tampilan yang optimal di berbagai perangkat

## 🛠️ Teknologi

- **PHP Native** (tanpa framework)
- **MySQL** - Database
- **Bootstrap 5** - UI Framework
- **Font Awesome** - Icons
- **MVC Pattern** - Arsitektur aplikasi

## 📁 Struktur Folder

```
spk-saham-topsis/
├── app/
│   ├── config/          # Konfigurasi aplikasi dan database
│   ├── controllers/     # Controller files
│   ├── helpers/         # Helper files (Flasher, TopsisHelper)
│   ├── models/          # Model files
│   └── views/           # View files (HTML/PHP)
├── core/                # Core system (App, Controller, Database)
├── database/            # SQL files (schema, seeder)
├── public/              # Public folder (entry point)
│   ├── assets/
│   │   ├── css/         # Stylesheets
│   │   ├── images/      # Images
│   │   └── js/          # JavaScript files
│   └── index.php        # Front controller
├── .htaccess            # Apache URL rewriting
├── index.php            # Redirect ke public/
└── README.md            # Dokumentasi
```

## ⚙️ Instalasi

### Prasyarat

- **PHP** >= 7.4
- **MySQL** >= 5.7
- **Apache** (dengan mod_rewrite enabled)
- **Composer** (optional)

### Langkah Instalasi

1. **Clone atau download repository**

   ```bash
   git clone <repository-url>
   cd spk-saham-topsis
   ```

2. **Setup Database**

   - Buat database baru di MySQL
   - Import file SQL dari folder `database/` (jika sudah ada)
   - Atau buat tabel secara manual sesuai kebutuhan

3. **Konfigurasi Database**

   Edit file `app/config/database.php`:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'db_rekomendasi_saham');
   ```

4. **Konfigurasi Base URL**

   Edit file `app/config/config.php`:

   ```php
   define('BASE_URL', 'http://localhost/spk-saham-topsis/public/');
   ```

5. **Setup Virtual Host (Optional)**

   Untuk penggunaan lebih mudah, setup virtual host di Apache.

6. **Jalankan Aplikasi**

   Akses melalui browser:

   ```
   http://localhost/spk-saham-topsis/
   ```

## 📖 Cara Penggunaan

1. **Input Data Saham**: Masukkan data saham yang akan dianalisis
2. **Tentukan Kriteria**: Pilih kriteria yang akan digunakan (harga, volume, market cap, dll)
3. **Set Bobot**: Tentukan bobot untuk setiap kriteria
4. **Hitung TOPSIS**: Jalankan perhitungan TOPSIS
5. **Lihat Hasil**: Lihat ranking dan rekomendasi saham terbaik

## 🧮 Metode TOPSIS

TOPSIS adalah metode pengambilan keputusan yang didasarkan pada konsep bahwa alternatif terbaik memiliki jarak terpendek dari solusi ideal positif dan jarak terjauh dari solusi ideal negatif.

**Langkah-langkah TOPSIS:**

1. Normalisasi matriks keputusan
2. Normalisasi terbobot
3. Menentukan solusi ideal positif (A+) dan negatif (A-)
4. Menghitung jarak setiap alternatif ke A+ dan A-
5. Menghitung nilai preferensi (kedekatan relatif)
6. Ranking alternatif

## 📝 Catatan Pengembangan

- Database schema belum dibuat - akan ditambahkan setelah perancangan
- Helper TOPSIS berisi skeleton function - akan diimplementasi sesuai kebutuhan
- UI menggunakan Bootstrap 5 dengan CDN

## 👨‍💻 Developer

- **Version**: 1.0.0
- **Date**: December 10, 2025
- **License**: MIT

## 📞 Kontak & Support

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.

---

**Happy Coding! 🚀**
