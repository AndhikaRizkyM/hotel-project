<!-- Terdapat beberapa update yang ingin saya lakukan pada pemrosesan AI kali ini, antara lain :
1. Mengatur ulang flow laundry, jadi HK memproses laundry jika terdapat orderan laundry. dan pemrosesan laundry tersebut bisa dilihat oleh FO
2. Jika Ingin check in diharuskan melunaskan biaya reservasi terlebih dahulu, dan nanti ada pilihan mau memberikan jaminan berupa uang atau kartu identitas
3. Mengubah Flow Inspection Room oleh HK dibuat sebelum guest check-out, jadi nanti FO mengirimkan notifikasi untuk inspection room ke HK, dan HK melakukan inspection room. dan jika sudah selesai HK akan mengirimkan notifikasi ke FO untuk melanjutkan ke tahap check-out. Jika terdapat damage atau lost item, HK akan menginputkannya pada sistem dan akan di proses oleh FO dan dilanjutkan ke tahap negosiasi dengan guest untuk dilakukan pembayaran digabung dengan folio sebelumnya
4. Pemrosesan Inspection Room HK dibuat lebih mudah digunakan dan bisa tersambung semua mulai dari damage, lost and found, dan maintenance request ke fo atau admin, jadi jika ada kerusakan atau kehilangan barang, atau room perlu maintenance bisa langsung di update oleh HK dan dikirim ke Fo atau admin untuk dilakukan tindakan lebih lanjut
5. Atur ulang flow pemrosesan breakfast pada fnb, dibuat terlihat pemrosesannya pada FO dan fnb bisa melakukan update status dan ada timeline
6. Atur ulang design semua menu dibuat menjadi modern dan interactive, tapi dibuat tetap minimalis
7. Benerin burger menu, dikarenakan jika saya klik tidak tertutup semua. seperti ada error atau miss, tolong solve ya -->

Update Baru nih
1. Pada proses payment sebelum check-in, bisa ga modalnya berisi payment amount yang sudah otomatis keluar biaya reservasinya, dan untuk pemilihan jaminan juga bisa dimasukkan ke dalam modal ini juga atau gimana ya? tolong disesuaikan agar lebih bagus dan nyaman digunakan
2. Burger Menu belum diperbaiki
3. Bikin design lebih modern dan interactive, tapi dibuat tetap minimalis
4. Atur ulang layout guest folio dengan clean dan rapi, dan pastikan semua transaksi terlihat dengan jelas
5. ubah semua bahasa menjadi bahasa inggris, jangan ada ambiguitas pada bahasa dikarenakan ada bahasa indo dan inggris
6. Tolong perbaiki flow laundry order, ini yang melakukan pekerjaan laundry siapa ya?

---

## Brief of Updates & Implementation Plan

### 1. Unified Settle & Check-In Modal
* **Description**: Create a single "Check-In" action modal that combines outstanding balance settlement (payment details) and guest guarantee collection (guarantee type & detail) into one smooth flow.
* **Proposed Changes**:
  * Modify [ReservationController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/ReservationController.php) `checkIn()` method to accept optional payment fields (`amount`, `payment_method`) alongside guarantee details. If `amount` is provided, it processes the payment first, verifies the reservation is fully paid, and then checks in the guest.
  * Redesign the check-in card in [show.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/reservations/show.blade.php) to display a single "Process Check-In" button opening a unified modal (`checkInModal`).

### 2. Sidebar Burger Menu Fix
* **Description**: Fix the burger menu not closing completely on mobile by adding inline click handlers (`onclick="document.body.classList.remove('sidebar-open')"`) directly to both the close button and the backdrop. This guarantees closing functionality without early returns or JS errors in `main.js`.
* **Proposed Changes**:
  * Update close button in [sidebar.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/sidebar.blade.php) and backdrop in [admin.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/admin.blade.php).

### 3. Premium & Modern UI Enhancements (Minimalist & Interactive)
* **Description**: Polish the styling across all views with soft HSL color palettes, refined borders, micro-interactions, and professional layouts.
* **Proposed Changes**:
  * Update layouts, buttons, and badges in [show.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/reservations/show.blade.php), [hub.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/housekeeping/hub.blade.php), [breakfast.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/fb/breakfast.blade.php), and [index.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/laundry/index.blade.php).

### 4. Redesigned Guest Folio Ledger
* **Description**: Restructure the guest folio statement on the reservation details view into a clean, modern statement layout. Group charges logically and present balances clearly.
* **Proposed Changes**:
  * Update [show.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/reservations/show.blade.php) guest folio ledger table and summary.

### 5. English Language Standardization (Remove Ambiguity)
* **Description**: Translate all remaining Indonesian labels, alerts, validation warnings, and status badges in Blade templates and Controller responses to English.
* **Proposed Changes**:
  * Scan and translate views: [show.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/reservations/show.blade.php), [hub.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/housekeeping/hub.blade.php), [breakfast.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/fb/breakfast.blade.php), and controllers: [ReservationController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/ReservationController.php), [HousekeepingController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/HousekeepingController.php), [FbController.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers/FbController.php).

### 6. Laundry Board Link
* **Description**: Clarify that Housekeeping processes laundry and add a direct link to the **Laundry Board** (`/hk/laundry`) in [sidebar.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/sidebar.blade.php) for HK and Admin roles to make status updates easy.
* **Proposed Changes**:
  * Add navigation item to [sidebar.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/sidebar.blade.php).