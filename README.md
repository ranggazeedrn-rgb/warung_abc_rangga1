# 🛒 Warung ABC - Web Application Management System

Aplikasi manajemen toko dan inventaris berbasis PHP Native & MySQL dengan antarmuka **Modern Slate UI** yang bersih, responsif, dan mudah digunakan. Sistem ini mendukung *Multi-Role Access Control* untuk Admin, Kasir, dan Petugas Gudang.

---

## 🚀 Fitur Utama

- **Authentication & Multi-Role Support**:
  - **Admin**: Akses penuh ke seluruh sistem dan monitoring aktivitas[cite: 6, 10].
  - **Kasir**: Halaman khusus transaksi penjualan dan pencetakan ringkasan tagihan.
  - **Gudang**: Panel khusus monitoring stok, deteksi stok menipis/habis, serta pembaruan data barang.
- **Manajemen Inventaris**: Tambah, edit, restok, dan hapus data barang[cite: 4, 9, 11].
- **Sistem Transaksi Real-time**: Keranjang belanja interaktif dengan kalkulasi kembalian otomatis[cite: 6].
- **Activity Logging**: Pencatatan riwayat aktivitas pengguna (login, logout, edit barang, hapus barang) ke database[cite: 4, 8, 10].
- **UI/UX Modern**: Desain berbasis Slate & Inter Font dengan skema warna yang konsisten dan nyaman dipandang[cite: 6, 7].

---

## 🛠️ Teknologi yang Digunakan

- **PHP** (Native)[cite: 4, 5, 6, 8, 9, 10, 11]
- **MySQL** (Database Engine)[cite: 4, 6, 9, 10, 11]
- **HTML5 & CSS3** (Custom Styling Modern Slate UI)[cite: 6, 7]
- **JavaScript** (Kalkulasi Kembalian & Interaktivitas UI)[cite: 6]
- **Google Fonts** (Inter Font Family)[cite: 6, 7]

---

## 📁 Struktur Berkas Utama

```text
├── config/
│   └── koneksi.php              # Konfigurasi koneksi ke database MySQL
├── includes/
│   └── cek_session.php          # Middleware / proteksi sesi login[cite: 4, 6, 9]
├── data_barang.php              # Halaman kelola katalog barang (Admin/Gudang)[cite: 4, 9, 11]
├── edit_barang.php              # Form edit & restok informasi barang[cite: 11]
├── gudang.php                   # Panel utama monitoring stok barang (Role Gudang)
├── hapus_barang.php             # Script backend penghapusan barang[cite: 4]
├── index.php                    # Router utama & pengarah otomatis role[cite: 5]
├── kasir.php                    # Panel transaksi kasir & keranjang[cite: 6]
├── login.php                    # Halaman autentikasi akun[cite: 5, 7, 10]
├── logout.php                   # Script pemutusan sesi pengguna & pencatatan log[cite: 8]
├── proses_edit_barang.php       # Script backend pembaruan data barang[cite: 9]
├── proses_login.php             # Script validasi login & redirect role[cite: 10]
├── proses_simpan_transaksi.php  # Script pemrosesan transaksi kasir
└── README.md                    # Dokumentasi projek
