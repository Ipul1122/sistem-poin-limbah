<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PointRedemptionController as AdminRedemptionController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WasteCategoryController;
use App\Http\Controllers\Admin\WasteGradeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Peserta\PointRedemptionController as PesertaRedemptionController;
use App\Http\Controllers\Peserta\WasteTransactionController as PesertaTransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - EcoPoint Monolith
|--------------------------------------------------------------------------
*/

// Root URL redirect
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('peserta.dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes (Manual Auth via Auth::attempt)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Peserta Routes (Protected by Auth & Role: Peserta)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');

    // Transaksi Setor Limbah
    Route::get('/transactions', [PesertaTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [PesertaTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [PesertaTransactionController::class, 'store'])->name('transactions.store');

    // Penukaran Poin ke Rupiah
    Route::get('/redemptions', [PesertaRedemptionController::class, 'index'])->name('redemptions.index');
    Route::post('/redemptions', [PesertaRedemptionController::class, 'store'])->name('redemptions.store');
    Route::get('/redemptions/preview', [PesertaRedemptionController::class, 'preview'])->name('redemptions.preview');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected by Auth & Role: Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Verifikasi Transaksi Limbah
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/verify', [AdminTransactionController::class, 'verify'])->name('transactions.verify');

    // Master Data Kategori Sampah (Hanya Botol & Kaleng)
    Route::get('/categories', [WasteCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}/edit', [WasteCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [WasteCategoryController::class, 'update'])->name('categories.update');

    // Master Data Grade Kelayakan
    Route::get('/grades', [WasteGradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{grade}/edit', [WasteGradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{grade}', [WasteGradeController::class, 'update'])->name('grades.update');

    // Kelola Penukaran Poin Peserta ke Rupiah
    Route::get('/redemptions', [AdminRedemptionController::class, 'index'])->name('redemptions.index');
    Route::get('/redemptions/{redemption}', [AdminRedemptionController::class, 'show'])->name('redemptions.show');
    Route::post('/redemptions/{redemption}/complete', [AdminRedemptionController::class, 'complete'])->name('redemptions.complete');
    Route::post('/redemptions/{redemption}/reject', [AdminRedemptionController::class, 'reject'])->name('redemptions.reject');

    // Data Peserta
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});
