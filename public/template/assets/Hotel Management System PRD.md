PRODUCT REQUIREMENT DOCUMENT (PRD)
Hotel Management System
Version : 1.0
Platform : Web Application (Admin Panel)
Framework : Laravel 12
Database : MySQL
Frontend : Bootstrap 5
Authentication : Laravel Breeze
Template : AdminHMD Bootstrap Template

1. Project Overview
Project Name
Hotel Management System
Project Type
Internal Hotel Management Web Application
Project Purpose
Membangun sistem manajemen hotel berbasis web yang digunakan untuk mengelola operasional hotel mulai dari reservasi kamar, check-in, check-out, pengelolaan kamar, housekeeping, food & beverage, laundry, hingga pelaporan operasional hotel.
Target Users
Administrator
Front Office (FO)
Housekeeping (HK)
Food & Beverage (F&B)
Project Goals
Mempermudah proses reservasi kamar.
Mengelola status kamar secara real-time.
Mengurangi pencatatan manual.
Mempermudah koordinasi antar divisi.
Menyediakan laporan operasional hotel secara otomatis.
Menjadi pusat data seluruh aktivitas hotel.

2. Technology Stack
Backend
Laravel 12
PHP 8.3+
Frontend
Bootstrap 5
Blade Template
AdminHMD Bootstrap Template
Database
MySQL
Authentication & Authorization
Laravel Breeze
Spatie Laravel Permission
Additional Packages
Laravel Debugbar
Laravel Excel
DomPDF

3. User Roles
Administrator
Memiliki akses penuh terhadap seluruh sistem.
Front Office (FO)
Mengelola reservasi dan pelayanan tamu.
Housekeeping (HK)
Mengelola kebersihan dan kesiapan kamar.
Food & Beverage (F&B)
Mengelola pesanan makanan dan minuman.

4. Business Rules
Room Status
Code
Status
Description
A
Available
Kamar siap dijual
O
Occupied
Sedang ditempati tamu
D
Dirty
Belum dibersihkan
M
Maintenance
Sedang diperbaiki
R
Reserved
Sudah dibooking


Reservation Status
Code
Status
RSV
Reserved
CI
Checked In
CO
Checked Out
CAN
Cancelled
NS
No Show


Additional Rules
Kamar dengan status Maintenance tidak dapat dijual.
Kamar dengan status Dirty tidak dapat dijual.
Kamar baru dapat dijual jika status Available.
Setiap transaksi tambahan masuk ke Guest Folio.
Deposit dapat digunakan untuk mengurangi total tagihan saat checkout.
Setelah checkout, status kamar otomatis berubah menjadi Dirty.

5. Room Types
Standard Room
Capacity
2 Adults
Room Size
20 m²
Facilities
Queen Bed
TV
AC
WiFi
Bathroom
Shower
Mineral Water
Breakfast
Optional
Price
Rp350.000 / Night

Deluxe Room
Capacity
2 Adults
Room Size
25 m²
Facilities
Queen Bed
Smart TV
AC
WiFi
Work Desk
Bathroom
Shower
Breakfast
Optional
Price
Rp500.000 / Night

Superior Room
Capacity
2 Adults
Room Size
30 m²
Facilities
King Bed
Smart TV
AC
WiFi
Work Desk
Shower
Breakfast
Optional
Price
Rp700.000 / Night

Studio Room
Capacity
2 Adults
Room Size
35 m²
Facilities
King Bed
Sofa
Smart TV
Mini Pantry
Mini Fridge
Bathub
Breakfast
Included
Price
Rp950.000 / Night

Suite Room
Capacity
4 Adults
Room Size
50 m²
Facilities
Living Room
King Bed
Bathtub
Smart TV
Mini Pantry
Breakfast
Included
Price
Rp1.500.000 / Night

Connecting Room
Capacity
4 - 6 Adults
Room Size
60 m²
Facilities
Two Connected Rooms
Two Bathrooms
Smart TV
Mini Pantry
Family Area
Breakfast
Included
Price
Rp2.000.000 / Night

Extra Bed
Additional Cost
Rp150.000 / Night
Rules
Maksimal 2 extra bed per kamar.
Tidak semua room type mendukung extra bed.
Biaya otomatis masuk ke Guest Folio.

6. Functional Modules
Master Data
User Management
Create User
Update User
Delete User
Activate / Deactivate User
Role Management
Create Role
Update Role
Assign Permission
Room Type Management
Create Room Type
Update Room Type
Delete Room Type
Room Management
Create Room
Update Room
Change Status
Facility Management
Amenities
Breakfast
Extra Bed
F&B Menu Management
Food Menu
Beverage Menu
Laundry Service Management
Service Type
Service Price

Operational Modules
Reservation Management
Room Availability
Reservation
Reservation Form
Reservation History
Guest Management
Guest Profile
Guest History
Deposit Management
Deposit Payment
Deposit Refund
Check-In Management
Registration Form
Check-In Process
Guest Folio
Room Charges
F&B Charges
Laundry Charges
Extra Bed Charges
Miscellaneous Charges
Food & Beverage
Breakfast List
Room Service Order
Order Tracking
Laundry Management
Laundry Order
Laundry Tracking
Laundry Report
Extra Bed Management
Extra Bed Request
Installation Tracking
Housekeeping Management
Cleaning Task
Room Inspection
Cleaning History
Damage Report
Damage Report
Damage Cost
Lost & Found
Lost Item Report
Item Claim Tracking
Maintenance Management
Maintenance Request
Maintenance History
Check-Out Management
Invoice
Payment
Checkout Summary

Reporting Modules
Reservation Report
Occupancy Report
Revenue Report
Guest History Report
F&B Report
Laundry Report
Housekeeping Report
Maintenance Report
Check-In Report
Check-Out Report

7. UI / UX Guidelines
Design Style
Modern Hotel Dashboard
Minimalist
Clean
Professional
Responsive

Font
Primary Font:
Poppins
Fallback:
Sans Serif

Color Palette
Primary
#1E3A8A
Deep Hotel Blue

Secondary
#0EA5E9
Sky Blue

Success
#22C55E
Green

Warning
#F59E0B
Amber

Danger
#EF4444
Red

Background
#F8FAFC
Light Gray

Sidebar
#0F172A
Dark Navy

Card Style
Border Radius:
12px
Shadow:
Soft Shadow
Padding:
20px

Dashboard Widgets
Available Rooms
Occupied Rooms
Dirty Rooms
Check-In Today
Check-Out Today
Revenue Today
Pending Housekeeping Tasks
Pending F&B Orders


10. Alur Sistem
A. Room Availability Management
Sebelum proses reservasi dilakukan, sistem harus mampu menampilkan status seluruh kamar secara real-time.
Status Kamar
Available (siap dijual)
Reserved (sudah dibooking)
Occupied (sedang ditempati tamu)
Dirty (checkout dan belum dibersihkan)
Cleaning (sedang dibersihkan HK)
Maintenance (tidak dapat dijual)
Blocked (diblok untuk kebutuhan tertentu)
Flow
Sistem menampilkan daftar kamar dalam bentuk card.
Kamar diurutkan berdasarkan:
Lantai tertinggi ke terendah.
Nomor kamar terkecil ke terbesar.
Setiap card menampilkan:
Nomor kamar
Tipe kamar
Harga
Status kamar
FO dapat melakukan filter berdasarkan:
Tipe kamar
Status kamar
Lantai

B. Reservasi (Reservation)
FO menerima permintaan booking dari tamu secara walk-in.
Flow
FO membuka menu Room Availability.
FO mencari kamar yang tersedia.
FO memilih kamar yang akan dipesan.
Sistem menampilkan detail kamar:
Room Type
Harga kamar
Kapasitas
Fasilitas kamar
Breakfast Included
Extra Bed Availability
Amenities
FO memilih tanggal check-in dan check-out.
Sistem menghitung:
Room Charge
Pajak
Service Charge
Biaya tambahan lainnya
FO mengisi data tamu:
Nama Lengkap
NIK / Passport
Tanggal Lahir
Jenis Kelamin
Alamat
Negara
Nomor Telepon
Email
Kendaraan (opsional)
Nomor Plat Kendaraan (opsional)
Sistem membuat nomor reservasi secara otomatis.
Sistem menyimpan reservasi.
Status reservasi berubah menjadi:
Confirmed
Status kamar berubah menjadi:
Reserved
Sistem menghasilkan:
Reservation Form
Reservation Number
Reservation Detail
Output
Reservation Form (Printable)
Reservation Detail

C. Pembayaran Deposit
Sebelum check-in, FO dapat menerima pembayaran deposit.
Flow
FO membuka detail reservasi.
FO memilih menu Deposit.
Sistem menghitung jumlah deposit.
FO memilih metode pembayaran:
Cash
Debit Card
Credit Card
Transfer
QRIS
Sistem menyimpan transaksi deposit.
Sistem mencetak bukti pembayaran deposit.
Output
Deposit Receipt
Deposit History

D. Check-In
FO melakukan proses check-in saat tamu datang.
Flow
FO mencari data reservasi.
Sistem menampilkan detail reservasi.
FO melakukan verifikasi identitas tamu.
FO memastikan pembayaran atau deposit telah diterima.
FO melakukan proses check-in.
Sistem menghasilkan Registration Form.
Tamu menandatangani Registration Form.
Sistem menyimpan data check-in.
Status reservasi berubah menjadi:
Checked In
Status kamar berubah menjadi:
Occupied
Sistem membuat Guest Folio.
Output
Registration Form
Guest Folio

E. Guest Folio Management
Guest Folio digunakan untuk mencatat seluruh transaksi tamu selama menginap.
Transaksi Yang Masuk Ke Folio
Room Charge
Breakfast
Food & Beverage
Extra Bed
Laundry
Damage Charge
Lost Item Charge
Miscellaneous Charge
Flow
Sistem membuat Guest Folio saat check-in.
Setiap transaksi tambahan otomatis masuk ke folio.
FO dapat melihat total tagihan tamu secara real-time.
Output
Folio Detail
Folio Statement

F. Food & Beverage (F&B)
Tamu dapat memesan makanan atau minuman selama menginap.
Flow Breakfast
Reservasi memiliki fasilitas breakfast.
Sistem otomatis mengirim data breakfast ke dashboard F&B.
F&B dapat melihat daftar tamu breakfast berdasarkan tanggal.
Flow Room Service
Tamu memesan makanan/minuman melalui FO.
FO membuat order F&B.
Sistem mengirim order ke dashboard F&B.
Status order:
Pending
Preparing
Ready
Delivered
Cancelled
Setelah status Delivered:
Tagihan otomatis masuk ke Guest Folio.
Output
F&B Order
Kitchen Order Ticket
F&B Sales Report



G. Laundry Management
Laundry Management digunakan untuk menangani layanan pencucian pakaian milik tamu selama masa menginap.
Seluruh transaksi laundry akan tercatat pada Guest Folio dan ditagihkan saat proses check-out.
Jenis Layanan Laundry
Wash Only
Dry Clean
Iron Only
Express Laundry
Regular Laundry
Flow
Tamu menghubungi Front Office untuk layanan laundry.
FO membuat Laundry Order.
FO memilih:
Nama Tamu
Nomor Kamar
Tanggal Order
Jenis Layanan Laundry
FO menginput item laundry:
Kemeja
Kaos
Celana
Jaket
Dress
Handuk
Item lainnya
Sistem menghitung total biaya laundry.
Sistem membuat Laundry Request Form.
Housekeeping atau Laundry Staff menerima tugas pengambilan laundry.
Laundry Staff mengambil pakaian dari kamar tamu.
Status laundry berubah menjadi:
Collected
Laundry Staff melakukan proses pencucian.
Status laundry berubah menjadi:
Washing
Setelah selesai dicuci:
Status berubah menjadi:
Ready
Laundry Staff mengantarkan laundry ke kamar tamu.
Status laundry berubah menjadi:
Delivered
Sistem otomatis memasukkan biaya laundry ke Guest Folio.
Status Laundry
Pending
Collected
Washing
Ready
Delivered
Cancelled
Output
Laundry Request Form
Laundry Receipt
Laundry History
Laundry Sales Report

Laundry Item Damage Report
Jika terdapat kerusakan atau kehilangan pakaian selama proses laundry.
Flow
Laundry Staff membuat laporan kerusakan atau kehilangan.
Staff mengisi:
Nama Tamu
Nomor Kamar
Item
Jenis Masalah
Keterangan
Sistem mengirim laporan ke Front Office dan Management.
Management melakukan proses kompensasi kepada tamu.
Output
Laundry Damage Report
Laundry Lost Item Report

Laundry Dashboard
Laundry Staff dapat memonitor seluruh order laundry.
Informasi
Pending Orders
Collected Orders
Washing Orders
Ready Orders
Delivered Orders
Output
Laundry Operational Dashboard



H. Extra Bed Request
Tamu dapat meminta tambahan extra bed selama masa menginap.
Flow
Tamu menghubungi FO.
FO membuat permintaan extra bed.
Sistem menghitung biaya extra bed.
Sistem mencetak formulir permintaan extra bed.
Tamu menandatangani formulir.
Tagihan otomatis masuk ke Guest Folio.
Housekeeping menerima tugas pemasangan extra bed.
Output
Extra Bed Form
Extra Bed Charge

I. Housekeeping Management
Housekeeping bertanggung jawab terhadap kebersihan dan kesiapan kamar.
Flow
Housekeeping melihat daftar kamar:
Dirty
Cleaning
Occupied
Setelah kamar dibersihkan:
Status berubah menjadi Cleaning.
Setelah inspeksi selesai:
Status berubah menjadi Available.
Output
Housekeeping Task List
Room Cleaning History

J. Damage & Lost Item Report
Housekeeping dapat melaporkan kerusakan atau kehilangan barang.
Flow
HK membuka menu Inspection Report.
HK memilih kamar.
HK mengisi laporan:
Barang hilang
Barang rusak
Keterangan
Estimasi biaya
Sistem mengirim laporan ke FO.
FO dapat memasukkan biaya penggantian ke Guest Folio.
Output
Damage Report
Lost Item Report

K. Maintenance Management
Jika ditemukan kerusakan kamar yang menyebabkan kamar tidak dapat dijual.
Flow
HK membuat Maintenance Request.
Sistem mengubah status kamar menjadi:
Maintenance
Kamar tidak muncul pada daftar kamar yang dapat dibooking.
Engineering melakukan perbaikan.
Setelah selesai:
Status kamar kembali menjadi Available.
Output
Maintenance Request
Maintenance History

L. Check-Out
FO melakukan proses check-out ketika tamu selesai menginap.
Flow
Tamu datang ke Front Office.
FO membuka Guest Folio.
Sistem menghitung seluruh tagihan:
Room Charge
Food & Beverage
Extra Bed
Laundry
Damage Charge
Miscellaneous Charge
Sistem mengurangi deposit yang sudah dibayarkan.
Sistem menampilkan sisa tagihan.
FO menerima pembayaran akhir.
Sistem menghasilkan invoice.
FO melakukan proses checkout.
Status reservasi berubah menjadi:
Checked Out
Status kamar berubah menjadi:
Dirty
Sistem membuat tugas inspeksi untuk Housekeeping.
Output
Invoice
Payment Receipt
Checkout Summary

M. Room Inspection After Checkout
Setelah tamu check-out, Housekeeping wajib melakukan inspeksi kamar.
Flow
HK menerima tugas inspeksi.
HK memeriksa:
Kebersihan kamar
Barang hilang
Kerusakan
Barang tamu tertinggal
Jika ditemukan kerusakan:
Dibuat Damage Report.
Jika ditemukan barang tertinggal:
Dibuat Lost & Found Report.
Jika kamar layak dijual:
Status menjadi Available.
Jika terdapat kerusakan berat:
Status menjadi Maintenance.
Output
Inspection Report
Lost & Found Report

N. Cancellation & No Show
Cancellation
Tamu membatalkan reservasi.
FO melakukan pembatalan reservasi.
Status reservasi menjadi:
Cancelled
Status kamar kembali Available.
No Show
Tamu tidak datang hingga batas waktu check-in.
FO melakukan proses No Show.
Status reservasi menjadi:
No Show
Status kamar kembali Available.
Output
Cancellation Report
No Show Report
 

O. Reporting & Analytics
Reporting digunakan oleh Management, Supervisor, dan Front Office untuk memantau performa operasional hotel secara harian, bulanan, maupun tahunan.
Flow
User membuka menu Reports.
User memilih periode laporan:
Harian
Mingguan
Bulanan
Tahunan
Custom Date Range
Sistem melakukan pengolahan data dari seluruh modul:
Reservation
Check-In
Check-Out
Payment
Guest Folio
Food & Beverage
Housekeeping
Maintenance
Sistem menampilkan laporan dalam bentuk:
Table
Summary Card
Chart / Grafik
User dapat melakukan:
Filter
Search
Export PDF
Export Excel
Print Report

Reservation Report
Menampilkan seluruh data reservasi.
Informasi
Total Reservasi
Reservasi Aktif
Reservasi Cancelled
Reservasi No Show
Reservasi per Room Type
Reservasi per Periode
Output
Reservation Report PDF
Reservation Report Excel

Check-In Report
Menampilkan seluruh data tamu yang melakukan check-in.
Informasi
Total Check-In
Guest Check-In History
Check-In per Room Type
Check-In per Periode
Output
Check-In Report

Check-Out Report
Menampilkan seluruh data tamu yang melakukan check-out.
Informasi
Total Check-Out
Guest Stay Duration
Average Length of Stay
Output
Check-Out Report

Revenue Report
Menampilkan total pendapatan hotel.
Informasi
Room Revenue
Food & Beverage Revenue
Extra Bed Revenue
Laundry Revenue
Damage Charge Revenue
Other Revenue
Total Revenue
Output
Revenue Report
Revenue Summary

Occupancy Report
Menampilkan tingkat keterisian kamar hotel.
Informasi
Total Kamar
Kamar Terjual
Kamar Kosong
Occupancy Rate (%)
Rumus
Occupancy Rate =
(Room Occupied / Total Room Available) x 100%
Output
Occupancy Report

Guest History Report
Menampilkan riwayat tamu yang pernah menginap.
Informasi
Nama Tamu
Jumlah Kunjungan
Total Malam Menginap
Total Pengeluaran
Output
Guest History Report

Food & Beverage Report
Menampilkan aktivitas penjualan makanan dan minuman.
Informasi
Total Order
Menu Terlaris
Revenue F&B
Order per Periode
Output
F&B Sales Report

Housekeeping Report
Menampilkan aktivitas Housekeeping.
Informasi
Total Kamar Dibersihkan
Kamar Dirty
Kamar Cleaning
Kamar Available
Riwayat Pembersihan
Output
Housekeeping Report

Maintenance Report
Menampilkan aktivitas maintenance kamar.
Informasi
Total Maintenance Request
Kamar Maintenance
Riwayat Perbaikan
Estimasi Biaya Perbaikan
Output
Maintenance Report

Dashboard Summary
Dashboard menampilkan ringkasan operasional hotel secara real-time.
Informasi
Total Available Room
Total Occupied Room
Total Reserved Room
Total Dirty Room
Check-In Hari Ini
Check-Out Hari Ini
Revenue Hari Ini
Pending F&B Orders
Pending Housekeeping Tasks
Pending Maintenance Requests
Output
Operational Dashboard
Management Dashboard


11. Role & Permission Management
Sistem Hotel Management memiliki 4 jenis role utama yang digunakan untuk mengelola operasional hotel.

A. Admin / Superadmin
Admin memiliki akses penuh terhadap seluruh modul sistem.
Tujuan
Mengelola konfigurasi sistem, data master, transaksi, monitoring operasional, dan laporan.

Dashboard
Informasi
Total Kamar
Available Room
Occupied Room
Reserved Room
Dirty Room
Maintenance Room
Check-In Hari Ini
Check-Out Hari Ini
Revenue Hari Ini
Pending Housekeeping Task
Pending F&B Order

Menu Master Data
User Management
Fitur:
List User
Create User
Edit User
Nonaktifkan User
Reset Password

Role Management
Fitur:
List Role
Create Role
Edit Role
Permission Management

Room Type Management
Fitur:
List Room Type
Create Room Type
Edit Room Type
Delete Room Type
Activate / Deactivate

Room Management
Fitur:
List Room
Create Room
Edit Room
Change Status
Activate / Deactivate

Facility Management
Fitur:
Amenities
Breakfast
Extra Bed
Room Facilities

Food & Beverage Menu Management
Fitur:
List Menu
Create Menu
Edit Menu
Set Price
Activate / Deactivate

Laundry Service Management
Fitur:
Laundry Service List
Laundry Price List
Activate / Deactivate Service

Menu Operational
Reservation
Fitur:
View All Reservation
Edit Reservation
Cancel Reservation
No Show Reservation

Check-In
Fitur:
View All Check-In

Check-Out
Fitur:
View All Check-Out

Guest Folio
Fitur:
View Folio
Add Manual Charge
Adjust Charge

Payment
Fitur:
View Payment
Verify Payment
Refund Deposit

Housekeeping Monitoring
Fitur:
View Task
View Cleaning History
View Inspection Result

Maintenance
Fitur:
Create Maintenance Request
Update Maintenance Status
Complete Maintenance

Menu Report
Fitur:
Reservation Report
Occupancy Report
Revenue Report
Guest History Report
F&B Report
Laundry Report
Housekeeping Report
Maintenance Report

B. Front Office (FO)
FO bertanggung jawab terhadap seluruh aktivitas tamu mulai dari reservasi hingga check-out.

Dashboard
Informasi
Available Room
Reserved Room
Occupied Room
Arrival Today
Departure Today
Pending Payment

Room Availability
Fitur:
View Room Card
Filter Room
View Room Detail

Reservation
Fitur:
Create Reservation
Edit Reservation
Cancel Reservation
View Reservation
Print Reservation Form

Guest Management
Fitur:
Create Guest
Edit Guest
View Guest History

Deposit Management
Fitur:
Input Deposit
Print Deposit Receipt

Check-In
Fitur:
Check-In Guest
Print Registration Form

Guest Folio
Fitur:
View Folio
Add Charge
View Transaction History

Food & Beverage Order
Fitur:
Create F&B Order
View Order Status

Laundry Order
Fitur:
Create Laundry Order
View Laundry Status

Extra Bed Request
Fitur:
Create Extra Bed Request
Print Extra Bed Form

Check-Out
Fitur:
View Folio
Process Payment
Print Invoice
Checkout Guest

Lost & Found
Fitur:
View Lost Item Report

C. Housekeeping (HK)
Housekeeping bertanggung jawab terhadap kebersihan dan kesiapan kamar.

Dashboard
Informasi
Dirty Room
Cleaning Room
Available Room
Occupied Room
Pending Inspection

Room Status
Fitur:
View Room Status
Update Room Status

Cleaning Task
Fitur:
View Task List
Start Cleaning
Complete Cleaning

Room Inspection
Fitur:
Create Inspection Report
Update Inspection Result

Damage Report
Fitur:
Create Damage Report
Upload Evidence
View Report History

Lost & Found
Fitur:
Create Lost Item Report
Update Lost Item Status

Maintenance Request
Fitur:
Create Maintenance Request
View Maintenance Status

Extra Bed Task
Fitur:
View Extra Bed Request
Complete Installation

D. Food & Beverage (F&B)
F&B bertanggung jawab terhadap seluruh pesanan makanan dan minuman.

Dashboard
Informasi
Pending Orders
Preparing Orders
Ready Orders
Delivered Orders

Breakfast List
Fitur:
View Breakfast Guest List
Filter By Date

Order Management
Fitur:
View Incoming Order
Accept Order
Process Order
Complete Order

Order Status
Fitur:
Update Status:
Pending
Preparing
Ready
Delivered

Menu List
Fitur:
View Menu
View Price

Sales History
Fitur:
View Order History
View Daily Sales

F&B Report
Fitur:
Daily Sales Report
Monthly Sales Report


