<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\FbController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (which redirects to login if unauthenticated)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth', 'menu.access'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Unified dashboard (redirects based on role inside the controller)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // 1. Admin Master Data routes
    // ==========================================
    Route::middleware('role:Admin')->group(function () {
        // Users CRUD
        Route::get('/master/users', [MasterDataController::class, 'usersIndex'])->name('master.users.index');
        Route::get('/master/users/create', [MasterDataController::class, 'usersCreate'])->name('master.users.create');
        Route::post('/master/users', [MasterDataController::class, 'usersStore'])->name('master.users.store');
        Route::get('/master/users/{id}/edit', [MasterDataController::class, 'usersEdit'])->name('master.users.edit');
        Route::put('/master/users/{id}', [MasterDataController::class, 'usersUpdate'])->name('master.users.update');
        Route::post('/master/users/{id}/toggle', [MasterDataController::class, 'usersToggle'])->name('master.users.toggle');

        // Room Types CRUD
        Route::get('/master/room-types', [MasterDataController::class, 'roomTypesIndex'])->name('master.room-types.index');
        Route::get('/master/room-types/create', [MasterDataController::class, 'roomTypesCreate'])->name('master.room-types.create');
        Route::post('/master/room-types', [MasterDataController::class, 'roomTypesStore'])->name('master.room-types.store');
        Route::get('/master/room-types/{id}/edit', [MasterDataController::class, 'roomTypesEdit'])->name('master.room-types.edit');
        Route::put('/master/room-types/{id}', [MasterDataController::class, 'roomTypesUpdate'])->name('master.room-types.update');
        Route::delete('/master/room-types/{id}', [MasterDataController::class, 'roomTypesDestroy'])->name('master.room-types.destroy');

        // Rooms CRUD
        Route::get('/master/rooms', [MasterDataController::class, 'roomsIndex'])->name('master.rooms.index');
        Route::get('/master/rooms/create', [MasterDataController::class, 'roomsCreate'])->name('master.rooms.create');
        Route::post('/master/rooms', [MasterDataController::class, 'roomsStore'])->name('master.rooms.store');
        Route::get('/master/rooms/{id}/edit', [MasterDataController::class, 'roomsEdit'])->name('master.rooms.edit');
        Route::put('/master/rooms/{id}', [MasterDataController::class, 'roomsUpdate'])->name('master.rooms.update');
        Route::post('/master/rooms/{id}/toggle', [MasterDataController::class, 'roomsToggle'])->name('master.rooms.toggle');

        // F&B Menu CRUD
        Route::get('/master/fb-menus', [MasterDataController::class, 'fbMenusIndex'])->name('master.fb-menus.index');
        Route::get('/master/fb-menus/create', [MasterDataController::class, 'fbMenusCreate'])->name('master.fb-menus.create');
        Route::post('/master/fb-menus', [MasterDataController::class, 'fbMenusStore'])->name('master.fb-menus.store');
        Route::get('/master/fb-menus/{id}/edit', [MasterDataController::class, 'fbMenusEdit'])->name('master.fb-menus.edit');
        Route::put('/master/fb-menus/{id}', [MasterDataController::class, 'fbMenusUpdate'])->name('master.fb-menus.update');
        Route::post('/master/fb-menus/{id}/toggle', [MasterDataController::class, 'fbMenusToggle'])->name('master.fb-menus.toggle');

        // Laundry Services CRUD
        Route::get('/master/laundry-services', [MasterDataController::class, 'laundryServicesIndex'])->name('master.laundry-services.index');
        Route::get('/master/laundry-services/create', [MasterDataController::class, 'laundryServicesCreate'])->name('master.laundry-services.create');
        Route::post('/master/laundry-services', [MasterDataController::class, 'laundryServicesStore'])->name('master.laundry-services.store');
        Route::get('/master/laundry-services/{id}/edit', [MasterDataController::class, 'laundryServicesEdit'])->name('master.laundry-services.edit');
        Route::put('/master/laundry-services/{id}', [MasterDataController::class, 'laundryServicesUpdate'])->name('master.laundry-services.update');
        Route::post('/master/laundry-services/{id}/toggle', [MasterDataController::class, 'laundryServicesToggle'])->name('master.laundry-services.toggle');

        // QC Inspection Charges CRUD
        Route::get('/master/inspection-charges', [MasterDataController::class, 'inspectionChargesIndex'])->name('master.inspection-charges.index');
        Route::get('/master/inspection-charges/create', [MasterDataController::class, 'inspectionChargesCreate'])->name('master.inspection-charges.create');
        Route::post('/master/inspection-charges', [MasterDataController::class, 'inspectionChargesStore'])->name('master.inspection-charges.store');
        Route::get('/master/inspection-charges/{id}/edit', [MasterDataController::class, 'inspectionChargesEdit'])->name('master.inspection-charges.edit');
        Route::put('/master/inspection-charges/{id}', [MasterDataController::class, 'inspectionChargesUpdate'])->name('master.inspection-charges.update');
        Route::delete('/master/inspection-charges/{id}', [MasterDataController::class, 'inspectionChargesDestroy'])->name('master.inspection-charges.destroy');

        // Reports View & Exports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

        // Role Permissions CRUD
        Route::get('/master/roles', [RolePermissionController::class, 'index'])->name('master.roles.index');
        Route::post('/master/roles', [RolePermissionController::class, 'update'])->name('master.roles.update');
        Route::post('/master/roles/store', [RolePermissionController::class, 'store'])->name('master.roles.store');
        Route::put('/master/roles/{id}/update', [RolePermissionController::class, 'updateRole'])->name('master.roles.updateRole');
        Route::delete('/master/roles/{id}/delete', [RolePermissionController::class, 'destroyRole'])->name('master.roles.destroyRole');
    });

    // ==========================================
    // 2. Front Office routes
    // ==========================================
    Route::middleware('role:Admin,FO')->group(function () {
        Route::get('/fo/availability', [ReservationController::class, 'availability'])->name('fo.availability');
        
        // Bookings (Reservations)
        Route::get('/fo/reservations', [ReservationController::class, 'index'])->name('fo.reservations.index');
        Route::get('/fo/reservations/create', [ReservationController::class, 'create'])->name('fo.reservations.create');
        Route::post('/fo/reservations', [ReservationController::class, 'store'])->name('fo.reservations.store');
        Route::get('/fo/reservations/{id}', [ReservationController::class, 'show'])->name('fo.reservations.show');
        Route::post('/fo/reservations/{id}/check-in', [ReservationController::class, 'checkIn'])->name('fo.reservations.check-in');
        Route::post('/fo/reservations/{id}/check-out', [ReservationController::class, 'checkOut'])->name('fo.reservations.check-out');
        Route::post('/fo/reservations/{id}/request-inspection', [ReservationController::class, 'requestInspection'])->name('fo.reservations.request-inspection');
        Route::post('/fo/reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('fo.reservations.cancel');
        Route::post('/fo/reservations/{id}/no-show', [ReservationController::class, 'noShow'])->name('fo.reservations.no-show');
        Route::post('/fo/reservations/{id}/damage/{damageId}/{action}', [ReservationController::class, 'processDamageIssue'])->name('fo.reservations.damage-process');
        Route::post('/fo/reservations/{id}/lost/{lostId}/{action}', [ReservationController::class, 'processLostIssue'])->name('fo.reservations.lost-process');

        // Deposit actions
        Route::post('/fo/reservations/{id}/deposit', [DepositController::class, 'store'])->name('fo.reservations.deposit');
        Route::post('/fo/reservations/{id}/deposit/refund', [DepositController::class, 'refund'])->name('fo.reservations.refund');

        // Services booking from FO
        Route::post('/fo/reservations/{id}/extrabed', [ReservationController::class, 'storeExtrabed'])->name('fo.reservations.extrabed');
        Route::post('/fo/reservations/{id}/fb-order', [ReservationController::class, 'storeFbOrder'])->name('fo.reservations.fb-order');
        Route::post('/fo/reservations/{id}/laundry-order', [ReservationController::class, 'storeLaundryOrder'])->name('fo.reservations.laundry-order');

        // Guest profiles
        Route::get('/fo/guests/search', [ReservationController::class, 'guestsSearch'])->name('fo.guests.search');
        Route::get('/fo/guests', [ReservationController::class, 'guestsIndex'])->name('fo.guests.index');
        Route::post('/fo/guests', [ReservationController::class, 'guestsStore'])->name('fo.guests.store');
        Route::get('/fo/guests/{id}', [ReservationController::class, 'guestsShow'])->name('fo.guests.show');
        Route::put('/fo/guests/{id}', [ReservationController::class, 'guestsUpdate'])->name('fo.guests.update');
        Route::delete('/fo/guests/{id}', [ReservationController::class, 'guestsDestroy'])->name('fo.guests.destroy');

        // Print paths
        Route::get('/fo/print-registration/{id}', [ReservationController::class, 'printRegistration'])->name('fo.print-registration');
        Route::get('/fo/print-extrabed/{id}', [ReservationController::class, 'printExtrabed'])->name('fo.print-extrabed');
        Route::get('/fo/print-misc/{id}', [ReservationController::class, 'printMisc'])->name('fo.print-misc');
        Route::get('/fo/print-invoice/{id}', [ReservationController::class, 'printInvoice'])->name('fo.print-invoice');
    });

    // ==========================================
    // 3. Housekeeping routes
    // ==========================================
    Route::middleware('role:Admin,HK')->group(function () {
        Route::get('/hk/tasks', [HousekeepingController::class, 'tasksIndex'])->name('hk.tasks');
        Route::post('/hk/tasks/{id}/start', [HousekeepingController::class, 'startTask'])->name('hk.tasks.start');
        Route::post('/hk/tasks/{id}/complete', [HousekeepingController::class, 'completeTask'])->name('hk.tasks.complete');
        
        // Inspections
        Route::get('/hk/inspections', [HousekeepingController::class, 'inspectionsIndex'])->name('hk.inspections.index');
        Route::post('/hk/inspections', [HousekeepingController::class, 'storeInspection'])->name('hk.inspections.store');
        Route::post('/hk/inspections/pre-checkout', [HousekeepingController::class, 'storePreCheckoutInspection'])->name('hk.inspections.store-pre-checkout');
        
        // Damage Reports
        Route::get('/hk/damages', [HousekeepingController::class, 'damagesIndex'])->name('hk.damages.index');
        Route::post('/hk/damages', [HousekeepingController::class, 'storeDamage'])->name('hk.damages.store');
        
        // Lost & Found
        Route::get('/hk/lost-found', [HousekeepingController::class, 'lostFoundIndex'])->name('hk.lost-found.index');
        Route::post('/hk/lost-found', [HousekeepingController::class, 'storeLostFound'])->name('hk.lost-found.store');
        Route::post('/hk/lost-found/{id}/claim', [HousekeepingController::class, 'claimLostFound'])->name('hk.lost-found.claim');
        
        // Maintenance Request
        Route::get('/hk/maintenance', [HousekeepingController::class, 'maintenanceIndex'])->name('hk.maintenance.index');
        Route::post('/hk/maintenance', [HousekeepingController::class, 'storeMaintenance'])->name('hk.maintenance.store');
        Route::post('/hk/maintenance/{id}/complete', [HousekeepingController::class, 'completeMaintenance'])->name('hk.maintenance.complete');
        
        // Extra Bed complete
        Route::post('/hk/extrabed/{id}/complete', [HousekeepingController::class, 'completeExtrabedTask'])->name('hk.extrabed.complete');

        // Laundry Order operations
        Route::get('/hk/laundry', [LaundryController::class, 'index'])->name('hk.laundry.index');
        Route::post('/hk/laundry/{id}/status', [LaundryController::class, 'updateStatus'])->name('hk.laundry.status');
        Route::post('/hk/laundry/{id}/damage', [LaundryController::class, 'reportDamage'])->name('hk.laundry.damage');
    });

    // ==========================================
    // 4. Food & Beverage routes
    // ==========================================
    Route::middleware('role:Admin,FB')->group(function () {
        Route::get('/fb/breakfast', [FbController::class, 'breakfastList'])->name('fb.breakfast');
        Route::post('/fb/breakfast/{id}/status', [FbController::class, 'updateBreakfastStatus'])->name('fb.breakfast.status');
        Route::get('/fb/orders', [FbController::class, 'ordersIndex'])->name('fb.orders.index');
        Route::post('/fb/orders/{id}/status', [FbController::class, 'updateStatus'])->name('fb.orders.status');
    });
});




