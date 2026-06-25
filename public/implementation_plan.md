# Flow QC Inspection Sebelum Check-Out & Katalog Denda Kehilangan/Kerusakan

Rencana ini mengubah alur check-out tamu hotel dengan mewajibkan Housekeeping (HK) melakukan Quality Control (QC) Inspection sebelum Front Office (FO) dapat memproses check-out. Jika terdapat kehilangan atau kerusakan properti kamar selama inspeksi, biaya denda akan otomatis ditambahkan ke Guest Folio tamu dan masuk ke total tagihan check-out. Daftar denda/kehilangan dikelola dalam sebuah katalog master data yang dapat disesuaikan (editable).

## User Review Required

> [!IMPORTANT]
> - **Perubahan Alur Check-out:** Tamu yang berstatus Checked In (`CI`) tidak dapat melakukan check-out sampai Front Office mengajukan permintaan inspeksi kamar dan Housekeeping menyelesaikannya. Form check-out akan dikunci (disabled) di halaman Folio.
> - **Integrasi Denda Otomatis:** Biaya denda dari hasil inspeksi akan diposting sebagai `GuestFolioItem` (tipe `Damage Charge` atau `Lost Item Charge`) sehingga langsung memperbarui nilai `Outstanding Balance` di halaman check-out.
> - **Katalog Denda Master:** Administrator dapat mengelola daftar barang beserta denda standarnya (contoh: Handuk Hilang = Rp75.000). Saat menginput denda, staf HK akan memilih barang dari katalog, tetapi harganya tetap bisa diedit (disesuaikan) untuk kasus tertentu.

## Proposed Changes

---

### 1. Database & Migrations

#### [NEW] [2026_06_25_000000_create_inspection_charge_items_table.php](file:///c:/xampp/htdocs/hotel-project/database/migrations/2026_06_25_000000_create_inspection_charge_items_table.php)
- Membuat tabel master katalog `inspection_charge_items` untuk menyimpan daftar barang denda standar:
  - `id` (bigint, pk)
  - `item_name` (string) - Nama barang (misal: "Handuk Hilang / Rusak", "Remote TV Rusak")
  - `amount` (decimal, 12, 2) - Nominal denda default
  - `type` (string) - Jenis denda (`damage` untuk kerusakan, `loss` untuk kehilangan)
  - `timestamps`

#### [NEW] [2026_06_25_000001_add_reservation_id_to_housekeeping_tasks_table.php](file:///c:/xampp/htdocs/hotel-project/database/migrations/2026_06_25_000001_add_reservation_id_to_housekeeping_tasks_table.php)
- Menambahkan kolom `reservation_id` (foreign key, nullable) ke tabel `housekeeping_tasks` untuk menghubungkan tugas inspeksi dengan reservasi tamu aktif yang akan check-out.

---

### 2. Models & Relationships

#### [NEW] [InspectionChargeItem.php](file:///c:/xampp/htdocs/hotel-project/app/Models/InspectionChargeItem.php)
- Model baru untuk tabel `inspection_charge_items`.

#### [MODIFY] [HousekeepingTask.php](file:///c:/xampp/htdocs/hotel-project/app/Models/HousekeepingTask.php)
- Menambahkan `reservation_id` ke properti `$fillable`.
- Menambahkan relasi `reservation()`:
  ```php
  public function reservation(): BelongsTo
  {
      return $this->belongsTo(Reservation::class);
  }
  ```

#### [MODIFY] [Reservation.php](file:///c:/xampp/htdocs/hotel-project/app/Models/Reservation.php)
- Menambahkan relasi `housekeepingTasks()`:
  ```php
  public function housekeepingTasks(): HasMany
  {
      return $this->hasMany(HousekeepingTask::class);
  }
  ```

---

### 3. Routes (`routes/web.php`)

#### [MODIFY] [web.php](file:///c:/xampp/htdocs/hotel-project/routes/web.php)
- Menambahkan grup rute CRUD katalog denda bagi Admin:
  ```php
  Route::get('/master/inspection-charges', [MasterDataController::class, 'inspectionChargesIndex'])->name('master.inspection-charges.index');
  Route::get('/master/inspection-charges/create', [MasterDataController::class, 'inspectionChargesCreate'])->name('master.inspection-charges.create');
  Route::post('/master/inspection-charges', [MasterDataController::class, 'inspectionChargesStore'])->name('master.inspection-charges.store');
  Route::get('/master/inspection-charges/{id}/edit', [MasterDataController::class, 'inspectionChargesEdit'])->name('master.inspection-charges.edit');
  Route::put('/master/inspection-charges/{id}', [MasterDataController::class, 'inspectionChargesUpdate'])->name('master.inspection-charges.update');
  Route::delete('/master/inspection-charges/{id}', [MasterDataController::class, 'inspectionChargesDestroy'])->name('master.inspection-charges.destroy');
  ```
- Menambahkan rute permintaan inspeksi oleh FO:
  ```php
  Route::post('/fo/reservations/{id}/request-inspection', [ReservationController::class, 'requestInspection'])->name('fo.reservations.request-inspection');
  ```
- Menambahkan rute pengiriman laporan QC Pre-Checkout oleh HK:
  ```php
  Route::post('/hk/inspections/pre-checkout', [HousekeepingController::class, 'storePreCheckoutInspection'])->name('hk.inspections.store-pre-checkout');
  ```

---

### 4. Controllers

#### [MODIFY] [MasterDataController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/MasterDataController.php)
- Menambahkan method CRUD katalog denda:
  - `inspectionChargesIndex()`
  - `inspectionChargesCreate()`
  - `inspectionChargesStore(Request $request)`
  - `inspectionChargesEdit($id)`
  - `inspectionChargesUpdate(Request $request, $id)`
  - `inspectionChargesDestroy($id)`

#### [MODIFY] [ReservationController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/ReservationController.php)
- Implementasi method `requestInspection($id)`:
  - Validasi reservasi berstatus `CI` (Checked In).
  - Pastikan tidak ada tugas inspeksi pre-checkout yang sedang berjalan untuk kamar ini.
  - Buat `HousekeepingTask` baru dengan `task_type = 'inspection'`, `status = 'pending'`, dan `reservation_id = $id`.
- Modifikasi method `checkOut(Request $request, $id)`:
  - Validasi bahwa tugas inspeksi dengan `task_type = 'inspection'` terkait reservasi ini harus ada dan berstatus `completed`. Jika tidak ada/belum selesai, return back dengan error.

#### [MODIFY] [HousekeepingController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/HousekeepingController.php)
- Pada `tasksIndex`:
  - Muat relasi `reservation.guest` pada query `$tasks` untuk menampilkan nama tamu pada tugas inspeksi pre-checkout.
  - Ambil semua data katalog `InspectionChargeItem::all()` untuk dilempar ke view agar bisa digunakan pada form modal inspeksi.
- Implementasi method `storePreCheckoutInspection(Request $request)`:
  - Validasi `housekeeping_task_id`, `result` (`passed` / `failed`), `notes`, dan array `charges` jika ada kerusakan/kehilangan.
  - Cari tugas `HousekeepingTask` dan relasi `Reservation`-nya.
  - Buat entri `RoomInspection` (relasi bawaan sistem).
  - Update status tugas HK menjadi `completed` dan `end_time = now()`.
  - Jika ada denda yang diinputkan dalam request:
    - Untuk setiap item denda (nama barang, nominal, kuantitas, jenis):
      - Hitung total denda = `amount * qty`.
      - Buat `GuestFolioItem` baru pada folio tamu: `item_type = (jenis == 'damage' ? 'Damage Charge' : 'Lost Item Charge')`, `description = "QC Denda - [Nama Barang] (x[Qty])"`, `amount = total denda`.
      - Jika jenis denda adalah kerusakan (`damage`), buat juga entri di tabel `damage_reports` untuk pencatatan riwayat kerusakan kamar.
      - Jika jenis denda adalah kehilangan (`loss`), buat entri di tabel `lost_found_reports` dengan deskripsi kehilangan properti kamar dan status `lost` untuk logging internal.

---

### 5. Views & UI

#### [NEW] [master/inspection-charges] Views
- Membuat folder baru `resources/views/master/inspection-charges/` berisi:
  - `index.blade.php`: Tabel list katalog denda (dengan kolom Nama Barang, Tipe Denda, Nominal Default, Aksi Edit/Hapus, serta tombol Tambah).
  - `create.blade.php`: Form input barang denda baru.
  - `edit.blade.php`: Form edit item katalog denda.

#### [MODIFY] [layouts/partials/sidebar.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/sidebar.blade.php)
- Menambahkan link menu **"QC Charge Catalog"** di bawah sub-menu Master Data (khusus akun Admin).

#### [MODIFY] [reservations/show.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/reservations/show.blade.php)
- Pada bagian Check-Out Settlement Panel:
  - Tambahkan logika pemeriksaan status inspeksi pre-checkout.
  - **Kondisi 1 (Belum ada inspeksi):** Tampilkan pesan peringatan berwarna kuning bahwa kamar belum diinspeksi. Tampilkan tombol **"Minta Inspeksi Kamar (QC)"** yang mengirim POST request ke rute `fo.reservations.request-inspection`. Form settlement pembayaran dan tombol check-out dinonaktifkan (disabled).
  - **Kondisi 2 (Inspeksi sedang berjalan):** Tampilkan pesan info berwarna biru bahwa inspeksi QC kamar sedang berlangsung oleh tim Housekeeping. Form settlement dan check-out tetap dinonaktifkan.
  - **Kondisi 3 (Inspeksi selesai):** Tampilkan pesan sukses berwarna hijau bahwa inspeksi selesai (Passed). Aktifkan form settlement pembayaran dan tombol check-out. Jika ada denda dari inspeksi, tagihan tersebut akan otomatis muncul di tabel Folio Ledger di atasnya dan memperbarui nilai Outstanding Balance.

#### [MODIFY] [housekeeping/hub.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/housekeeping/hub.blade.php)
- Di bagian tabel tugas aktif:
  - Jika tugas memiliki `reservation_id`, ganti label tipe tugas menjadi **"QC Pre-Checkout Room [Room No]"** dan tunjukkan nama tamu (misal: *Guest: John Doe*).
  - Untuk tombol aksi: jika status tugas belum `completed`, tampilkan tombol **"QC Pre-Checkout"** berwarna kuning. Tombol ini akan memanggil fungsi JavaScript `openPreCheckoutModal` untuk membuka form modal pengisian hasil QC.
- Membuat Modal Form **"Pre-Checkout QC Inspection"**:
  - Berisi pilihan hasil inspeksi (Passed / Failed) dan textarea catatan (notes).
  - Panel dinamis **"Input Kerusakan / Kehilangan Barang"**:
    - Tombol **"+ Tambah Item Denda"**.
    - Menggunakan JavaScript untuk menambah/menghapus baris item denda secara dinamis.
    - Setiap baris memiliki:
      - Dropdown pilihan barang denda dari katalog (ketika dipilih, harga standar denda langsung terisi di input harga).
      - Input kuantitas (qty).
      - Input harga satuan (editable, sehingga harga standar denda bisa dikustomisasi secara manual).
      - Dropdown jenis denda (`Damage Charge` atau `Lost Item Charge`).
      - Tombol hapus baris.
      - Total denda per baris otomatis terhitung.
  - Tombol Submit untuk mengirim data ke `hk.inspections.store-pre-checkout`.

---

## Verification Plan

### Automated Tests
- Menjalankan migrasi database baru:
  ```powershell
  php artisan migrate
  ```
- Memastikan tidak ada error saat me-seed ulang database jika diperlukan:
  ```powershell
  php artisan db:seed
  ```

### Manual Verification
1. **Pengujian Katalog Denda (Admin):**
   - Login sebagai Admin, masuk ke menu **QC Charge Catalog**.
   - Tambah beberapa item denda standar (contoh: "Handuk Mandi" seharga Rp75.000 tipe loss, "Gelas Pecah" seharga Rp25.000 tipe damage).
   - Edit salah satu item, pastikan berhasil terupdate.
2. **Pengujian Permintaan QC oleh FO:**
   - Login sebagai FO, masuk ke detail reservasi tamu berstatus Checked In (`CI`).
   - Verifikasi bahwa panel Check-Out terkunci dan menampilkan peringatan bahwa kamar belum diinspeksi.
   - Klik **Minta Inspeksi Kamar (QC)**. Verifikasi bahwa pesan berubah menjadi "Inspeksi QC sedang berlangsung" dan form checkout tetap terkunci.
3. **Pengujian Pengisian Hasil QC oleh Housekeeping:**
   - Login sebagai HK (atau gunakan akun Admin), buka **Housekeeping Hub**.
   - Cari tugas inspeksi pre-checkout yang baru dibuat.
   - Klik **QC Pre-Checkout**. Isi catatan, lalu tambahkan item denda (misal: pilih "Handuk Mandi", otomatis muncul Rp75.000, ubah harganya menjadi Rp80.000, ubah qty jadi 2).
   - Kirim hasil QC. Pastikan tugas berubah status menjadi `Completed` di list.
4. **Pengujian Check-out dengan Denda (FO):**
   - Kembali ke halaman detail reservasi tadi di akun FO.
   - Pastikan status inspeksi sekarang berwarna hijau ("Passed") dan tombol check-out aktif.
   - Periksa tabel Folio Ledger, pastikan item denda **"QC Denda - Handuk Mandi (x2)"** sebesar **Rp160.000** (Rp80.000 * 2) telah masuk ke tagihan dan terakumulasi ke Outstanding Balance.
   - Selesaikan proses check-out. Pastikan reservasi sukses berpindah status menjadi `Checked Out` (`CO`) dan room status berubah menjadi `Dirty` (`D`) dengan dibuatnya tugas pembersihan pasca check-out baru.
