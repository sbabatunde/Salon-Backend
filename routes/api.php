<?php
// routes/api.php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogPost;
use App\Http\Controllers\Booking;
use App\Http\Controllers\ClientReview;
use App\Http\Controllers\Clients;
use App\Http\Controllers\HeroVideo;
use App\Http\Controllers\Inventory;
use App\Http\Controllers\Portfolio;
use App\Http\Controllers\Service;
use App\Http\Controllers\Settings;
use App\Http\Controllers\Statistics;
use App\Http\Controllers\Style;
use App\Http\Controllers\StylistController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
  // Route::post('/login', [AuthController::class, 'login']);
  // Route::post('/register', [AuthController::class, 'register']);
  // Route::post('/logout', [AuthController::class, 'logout']);
  Route::get('/user', [AuthController::class, 'user']);


  // Appointments Booking Routes 
  Route::prefix('appointments')->name('book.')->group(function () {
    Route::post('book', [Booking::class, 'bookAppointment'])->name('appointment');
    Route::get('list', [Booking::class, 'listAppointments'])->name('list');
    Route::put('update/{id}', [Booking::class, 'updateAppointment'])->name('update');
  });


  // Appointments Booking Routes
  Route::prefix('stylist')->name('stylist.')->group(function () {
    Route::get('/', [StylistController::class, 'adminIndex'])->name('index');
    Route::post('/', [StylistController::class, 'store'])->name('store');
    Route::put('/{id}', [StylistController::class, 'update'])->name('update');
    Route::delete('/{id}', [StylistController::class, 'destroy'])->name('destroy');
    Route::post('/update-order', [StylistController::class, 'updateOrder'])->name('updateOrder');
  });

  // Clients Routes
  Route::prefix('clients')->name('clients.')->group(function () {
    Route::get('/', [Clients::class, 'index'])->name('list');
    Route::get('/{id}', [Clients::class, 'show'])->name('show');
    Route::put('/{id}', [Clients::class, 'update'])->name('update');
    Route::delete('/{id}', [Clients::class, 'destroy'])->name('delete');

    // Financial routes
    Route::post('/{id}/financial', [Clients::class, 'addFinancialRecord'])->name('financial.add');
    Route::get('/financial/summary', [Clients::class, 'getFinancialSummary'])->name('financial.summary');
  });

  // Dashboard Statistics Routes
  Route::prefix('dashboard')->group(function () {
    Route::get('/statistics', [Statistics::class, 'dashboardStatistics']);
    Route::get('/revenue-chart', [Statistics::class, 'revenueChartData']);
    Route::get('/appointment-trends', [Statistics::class, 'appointmentTrends']);
    Route::get('/service-performance', [Statistics::class, 'servicePerformance']);
    Route::get('/client-demographics', [Statistics::class, 'clientDemographics']);
    Route::get('/financial-insights', [Statistics::class, 'financialInsights']);
  });

  // Services Routes 
  Route::prefix('services')->name('services.')->group(function () {
    Route::post('create', [Service::class, 'newService'])->name('create');
    Route::get('list', [Service::class, 'listServices'])->name('list');
    Route::put('update/{id}', [Service::class, 'updateService'])->name('update');
    Route::delete('delete/{id}', [Service::class, 'delete'])->name('delete');
  });
  // In your routes/api.php
  Route::get('/styles/test-intervention', [Style::class, 'testIntervention']);

  // Appointments Booking Routes 
  Route::prefix('inventory')->name('inventory.')->group(function () {
    Route::post('create', [Inventory::class, 'create'])->name('create');
    Route::get('list', [Inventory::class, 'show'])->name('list');
    Route::put('update/{id}', [Inventory::class, 'update'])->name('update');
    Route::delete('delete/{id}', [Inventory::class, 'delete'])->name('delete');
  });

  // Styles Routes 
  Route::prefix('styles')->name('styles.')->group(function () {
    Route::post('create', [Style::class, 'create'])->name('create');
    Route::get('show', [Style::class, 'show'])->name('show');
    Route::put('update/{id}', [Style::class, 'update'])->name('update');
    Route::delete('delete/{id}', [Style::class, 'delete'])->name('delete');
  });

  // Styles Routes 
  Route::prefix('blogs')->name('blogs.')->group(function () {
    Route::post('create', [BlogPost::class, 'create'])->name('create');
    Route::get('show/all', [BlogPost::class, 'showAll'])->name('show.all');
    Route::get('show/active', [BlogPost::class, 'showActive'])->name('show.active');
    Route::put('update/{id}', [BlogPost::class, 'update'])->name('update');
    Route::get('view/{id}', [BlogPost::class, 'view'])->name('view');
    Route::delete('delete/{id}', [BlogPost::class, 'delete'])->name('delete');
  });

  // Videos Routes 
  Route::prefix('videos')->name('videos.')->group(function () {
    Route::post('store', [HeroVideo::class, 'store'])->name('store');
    Route::get('show/all', [HeroVideo::class, 'index'])->name('show.all');
    Route::get('active', [HeroVideo::class, 'getActiveVideos'])->name('active');
    Route::put('toggle-status/{id}', [HeroVideo::class, 'toggleStatus'])->name('toggle-status');
    // Route::put('update/{id}', [HeroVideo::class, 'update'])->name('update');
    // Route::get('view/{id}', [HeroVideo::class, 'view'])->name('view');
    Route::delete('delete/{id}', [HeroVideo::class, 'destroy'])->name('delete');
  });

  // Portfolio Routes 
  Route::prefix('portfolio')->name('portfolio.')->group(function () {
    Route::post('store', [Portfolio::class, 'store'])->name('store');
    Route::get('index', [Portfolio::class, 'index'])->name('index');
    Route::get('show', [Portfolio::class, 'showActive'])->name('active');
    Route::put('update/{id}', [Portfolio::class, 'update'])->name('update');
    Route::get('toggle-status/{id}', [Portfolio::class, 'toggleStatus'])->name('toggle-status');
    Route::delete('delete/{id}', [Portfolio::class, 'destroy'])->name('delete');
  });

  // Portfolio Routes 
  Route::prefix('testimonials')->name('testimonails.')->group(function () {
    Route::post('create-invite', [ClientReview::class, 'createInvite'])->name('invite.create');
    Route::get('/', [ClientReview::class, 'index'])->name('index');
    Route::get('form/{token}', [ClientReview::class, 'showForm'])->name('show.form');
    Route::post('form/{token}', [ClientReview::class, 'submit'])->name('submit.form');
    // Route::put('update/{id}', [ClientReview::class, 'update'])->name('update');
    // Route::get('toggle-status/{id}', [ClientReview::class, 'toggleStatus'])->name('toggle-status');
    // Route::delete('delete/{id}', [ClientReview::class, 'destroy'])->name('delete');
  });

  // Admin Settings Routes
  Route::prefix('settings/business/details')->name('settings.')->group(function () {
    Route::post('create', [Settings::class, 'businessDetails'])->name('business.details');
    Route::put('update/{id}', [Settings::class, 'businessDetails'])->name('business.details.update');
    Route::get('fetch', [Settings::class, 'fetchBusinessDetails'])->name('business.fetch');
  });
});

Route::get('/health', function () {
  return response()->json([
    'status' => 'ok',
    'timestamp' => now(),
    'database' => DB::connection()->getDatabaseName() ? 'connected' : 'error'
  ]);
});
