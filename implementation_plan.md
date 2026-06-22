# Implementation Plan - Hotel Management System

This plan outlines the architecture, database schema, controllers, views, and printable forms required to implement the Hotel Management System based on the provided Product Requirement Document (PRD) and templates.

## User Review Required

> [!IMPORTANT]
> **Database:** We are using SQLite (`database/database.sqlite`) as it is pre-configured and runs out of the box in the Laravel environment without requiring external credentials.
> **PDF & Printing:** For printable forms (Registration, Extra Bed, Miscellaneous, and Invoice), we will use standard CSS `@media print` styling and browser-native printing (`window.print()`). This ensures pixel-perfect rendering that matches the DOCX structures, is extremely lightweight, and allows saving directly as PDF without binary PDF engine dependencies.
> **Roles & Authentication:** Since Laravel Breeze is not pre-installed, we will implement a clean, lightweight authentication controller system and custom middlewares matching the AdminHMD login/register forms. This provides full visual consistency without introducing complex dependencies.

## Open Questions

- *Do you have a preference for default login credentials for the demo?* We propose seeding:
  - Admin: `admin@ppkdhotel.com` (password: `admin123`)
  - Front Office: `fo@ppkdhotel.com` (password: `fo123`)
  - Housekeeping: `hk@ppkdhotel.com` (password: `hk123`)
  - F&B: `fb@ppkdhotel.com` (password: `fb123`)

---

## Proposed Changes

### Database & Migrations

We will define migrations for the following entities to support all the operational modules:

#### [NEW] [migrations](file:///c:/xampp/htdocs/hotel-project/database/migrations)
- **`create_room_types_table`**: Stores room types (Standard, Deluxe, etc.), capacity, size, price, and facility descriptions.
- **`create_rooms_table`**: Stores room numbers, floors, room type IDs, and status (Available, Occupied, Dirty, Cleaning, Maintenance, Reserved, Blocked).
- **`create_guests_table`**: Stores NIK/Passport, name, address, nationality, contact details, and vehicle license plates.
- **`create_reservations_table`**: Stores reservation details, stay dates, charges, tax, service fees, and status (Reserved, Checked In, Checked Out, Cancelled, No Show).
- **`create_deposits_table`**: Tracks deposits paid or refunded, along with payment methods (Cash, Card, QRIS, etc.).
- **`create_guest_folios_table`** and **`create_guest_folio_items_table`**: Guest folio to track all room charges, F&B, laundry, extra beds, damages, and misc items.
- **`create_fb_menus_table`**, **`create_fb_orders_table`**, and **`create_fb_order_items_table`**: F&B menu and room service order tracking.
- **`create_laundry_services_table`**, **`create_laundry_orders_table`**, and **`create_laundry_order_items_table`**: Laundry request items, service types, and tracking.
- **`create_housekeeping_tasks_table`** and **`create_room_inspections_table`**: Cleanings, inspections, and task history.
- **`create_damage_and_lost_found_reports_table`**: Damage, lost items, and claim status.
- **`create_maintenance_requests_table`**: Maintenance logs that set rooms to Maintenance status.

#### [NEW] [DatabaseSeeder.php](file:///c:/xampp/htdocs/hotel-project/database/seeders/DatabaseSeeder.php)
- Seed default users with their roles (Admin, FO, HK, F&B).
- Seed Room Types (Standard, Deluxe, Superior, Studio, Suite, Connecting Room) and Room data (~15 rooms).
- Seed F&B menu items and Laundry services.

---

### Models & Middlewares

#### [MODIFY] [User.php](file:///c:/xampp/htdocs/hotel-project/app/Models/User.php)
- Add `role` column ('Admin', 'FO', 'HK', 'FB') and helper methods to check roles.

#### [NEW] [Models](file:///c:/xampp/htdocs/hotel-project/app/Models)
- `RoomType`, `Room`, `Guest`, `Reservation`, `Deposit`, `GuestFolio`, `GuestFolioItem`, `FbMenu`, `FbOrder`, `FbOrderItem`, `LaundryService`, `LaundryOrder`, `LaundryOrderItem`, `HousekeepingTask`, `RoomInspection`, `DamageReport`, `LostFoundReport`, `MaintenanceRequest`.

#### [NEW] [RoleMiddleware.php](file:///c:/xampp/htdocs/hotel-project/app/Http/Middleware/RoleMiddleware.php)
- Middleware to restrict routes to specific roles (e.g., FO only, HK only, F&B only, Admin only).

---

### Controllers

#### [NEW] [Controllers](file:///c:/xampp/htdocs/hotel-project/app/Http/Controllers)
- **`AuthController`**: Handles login, register, profile update, and logout using AdminHMD's visual design.
- **`DashboardController`**: Renders custom statistics widgets and pending tasks tailored to each user role.
- **`MasterDataController`**: Full CRUD for Users, Room Types, Rooms, F&B Menu, and Laundry Services (accessible by Admin).
- **`ReservationController`**: Handles real-time room availability card layout, room filtering, booking submission, check-in, check-out, and cancellation/no-show.
- **`DepositController`**: Receives, refunds, and logs guest deposits.
- **`FolioController`**: Lists guest folio statements, adds manual charges/adjustments.
- **`FbController`**: F&B dashboard, room service ordering, kitchen tracking (Pending -> Preparing -> Ready -> Delivered).
- **`LaundryController`**: Creates laundry orders, tracking (Pending -> Collected -> Washing -> Ready -> Delivered), damage reporting.
- **`HousekeepingController`**: Cleaning tasks overview, inspection submissions, damage/lost items reports.
- **`MaintenanceController`**: Maintenance logs that toggle room availability.
- **`ReportController`**: Generates reports (Reservation, Occupancy, Revenue, Guest History, HK, F&B, etc.) with print/export functions.

---

### Views & Layouts

#### [NEW] [layouts/admin.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/admin.blade.php)
- Main shell layout incorporating the AdminHMD template. Includes the dynamic sidebar, top navbar, theme toggler (light/dark mode), and user profile dropdown.

#### [NEW] [views](file:///c:/xampp/htdocs/hotel-project/resources/views)
- **`auth/login.blade.php`** & **`auth/register.blade.php`**: Styled auth pages.
- **`dashboards/`**: Specific layout folders for Admin, FO, Housekeeping, and F&B.
- **`master/`**: Management views for rooms, room types, users, F&B menus, laundry services.
- **`reservations/`**: Real-time room grid, reservation form, history list.
- **`folios/`**: Dynamic statement ledger and charge addition panel.
- **`fb/`** & **`laundry/`**: Service ordering panels and staff operational dashboards.
- **`housekeeping/`** & **`maintenance/`**: Cleaning checklists, inspections, and repair logs.
- **`reports/`**: Analytics dashboard with filters and export hooks.

---

### Printable Forms & Layouts
We will build dedicated, standalone, printable blade layouts styled to match the provided DOCX templates exactly:

1. **Registration Form (`reservations/print-registration.blade.php`)**
   - Renders a clean print sheet with guest identity, check-in time, payment method, hotel regulations (no durians, non-smoking policy, safety boxes), and double signature lines (Guest, Check-in Agent).
2. **Extra Bed Form (`reservations/print-extrabed.blade.php`)**
   - Requisition detail sheet containing the Date, Time, Guest Name, Room, setup duration, qty, price, and copy designations (Guest/FO/Housekeeping).
3. **Miscellaneous Form (`folios/print-misc.blade.php`)**
   - Renders a printable voucher style sheet with description lines, cashier signature, and guest signature fields.
4. **Invoice / Bill Statement (`folios/print-invoice.blade.php`)**
   - Clean financial summary sheet showing detailed folio ledger, deposits paid, final outstanding balance, and signature sections.

---

## Verification Plan

### Automated Tests
- We will run `php artisan test` or build simple route verification tests if needed.
- We will execute database migration & seed command to ensure error-free schema creation:
  ```bash
  php artisan migrate:fresh --seed
  ```

### Manual Verification
- We will run the Laravel local development server:
  ```bash
  php artisan serve
  ```
- Use a browser subagent or manual checks to log in under each role (`Admin`, `FO`, `HK`, `F&B`) and verify that dashboards render appropriate information.
- Perform a complete guest lifecycle test:
  1. FO views availability, selects room, inputs guest info.
  2. FO takes deposit, prints deposit receipt.
  3. FO checks-in the guest and prints the **Registration Form**.
  4. Guest requests an Extra Bed, FO creates request, prints **Extra Bed Requisition Form**, HK completes installation.
  5. FO adds F&B room service order, F&B updates status, delivered order adds charge to Folio.
  6. FO adds Laundry order, Laundry updates status, delivered order adds charge to Folio.
  7. HK reports a room damage, cost adds to Folio.
  8. FO initiates check-out, reviews folio statement, prints **Invoice / Bill Statement**, processes final payment.
  9. HK performs room checkout inspection, registers inspection report, changes room status from Dirty to Available.
  10. Verify that reports reflect occupancy and revenue correctly.
