<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes([
    'verify'   => false,
    'register' => true,
    'reset'    => false
]);

Route::get('/', [HomeController::class, 'index'])->name('landingpages');
Route::get('/event/{slug}', [HomeController::class, 'detailEvent'])->name('detail-event');

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:user')->group(function () {
        Route::post('/booking/seats/{slug}', [CheckoutController::class, 'showSeatSelection'])->name('checkout.seats');
        Route::post('/checkout/{slug}/seats', [CheckoutController::class, 'processSeatSelection'])->name('checkout.process-seats');
        Route::get('/checkout/{slug}/payment', [CheckoutController::class, 'showPayment'])->name('checkout.payment');
        Route::post('/checkout/{slug}/payment', [CheckoutController::class, 'processPayment'])->name('checkout.process-payment');
        Route::get('/invoice/{reference}', [PaymentController::class, 'invoicePayment'])->name('payment.invoice');

        // User Dashboard
        Route::prefix('/user-dashboard')->group(function () {
            Route::get('/', [HomeController::class, 'userDashboard'])->name('user-dashboard');
        });
    });

    // Admin Dashboard
    Route::middleware('role:admin')->prefix('/admin-dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('admin-dashboard');

        // Organizer Management
        Route::get('/organizers', [DashboardController::class, 'viewOrganizer'])->name('organizers.index');
        Route::get('/organizers/create', [DashboardController::class, 'viewOrganizerAdd'])->name('organizers.create');
        Route::post('/organizers/create', [DashboardController::class, 'addOrganizer'])->name('organizers.store');
        Route::get('/organizers/update/{id}', [DashboardController::class, 'viewOrganizerUpdate'])->name('organizers.edit');
        Route::post('/organizers/update/{id}', [DashboardController::class, 'updateOrganizer'])->name('organizers.update');
        Route::post('/organizers/delete/{id}', [DashboardController::class, 'deleteOrganizer'])->name('organizers.delete');

        // Venue Management
        Route::get('/venues', [DashboardController::class, 'viewVenue'])->name('venues.index');
        Route::get('/venues/create', [DashboardController::class, 'viewVenueAdd'])->name('venues.create');
        Route::post('/venues/create', [DashboardController::class, 'addVenue'])->name('venues.store');
        Route::get('/venues/update/{id}', [DashboardController::class, 'viewVenueUpdate'])->name('venues.edit');
        Route::post('/venues/update/{id}', [DashboardController::class, 'updateVenue'])->name('venues.update');
        Route::post('/venues/delete/{id}', [DashboardController::class, 'deleteVenue'])->name('venues.delete');

        // Charts
        Route::get('/stats/{year}/{month}', [DashboardController::class, 'getMonthlyStats']);

        // App Settings
        Route::get('/app-settings', [DashboardController::class, 'viewAppSettings'])->name('settings.index');
        Route::post('/app-settings', [DashboardController::class, 'updateAppSettings'])->name('settings.update');
    });

    // Organizer Dashboard
    Route::middleware('role:organizer')->prefix('/organizer-dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'organizerDashboard'])->name('organizer-dashboard');
        Route::get('/sales-data/{period}', [DashboardController::class, 'getSalesData']);

        // Event Management
        Route::get('/events', [DashboardController::class, 'viewOrganizerEvent'])->name('events.index');
        Route::get('/events/create', [DashboardController::class, 'viewOrganizerEventAdd'])->name('events.create');
        Route::post('/events/create', [DashboardController::class, 'addOrganizerEvent'])->name('events.store');
        Route::get('/events/update/{id}', [DashboardController::class, 'viewOrganizerEventUpdate'])->name('events.edit');
        Route::post('/events/update/{id}', [DashboardController::class, 'updateOrganizerEvent'])->name('events.update');
        Route::post('/events/delete/{id}', [DashboardController::class, 'deleteOrganizerEvent'])->name('events.delete');
    });
});
