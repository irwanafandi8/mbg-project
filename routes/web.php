<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login', [LoginController::class, 'showLoginForm']);
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('super-admin')
    ->name('super_admin.')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        // Admin Management (admin sekolah)
        Route::get('/admins', [SuperAdmin\AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [SuperAdmin\AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [SuperAdmin\AdminController::class, 'store'])->name('admins.store');
        Route::get('/admins/{user}/edit', [SuperAdmin\AdminController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{user}', [SuperAdmin\AdminController::class, 'update'])->name('admins.update');
        Route::patch('/admins/{user}/toggle-status', [SuperAdmin\AdminController::class, 'toggleStatus'])->name('admins.toggle-status');
        Route::delete('/admins/{user}', [SuperAdmin\AdminController::class, 'destroy'])->name('admins.destroy');

        // Complaints
        Route::get('/complaints', [Admin\ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [Admin\ComplaintController::class, 'show'])->name('complaints.show');
        Route::patch('/complaints/{complaint}/status', [Admin\ComplaintController::class, 'updateStatus'])->name('complaints.update-status');

        // Categories
        Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::patch('/categories/{category}/toggle-status', [Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

        // Schools
        Route::get('/schools', [Admin\SchoolController::class, 'index'])->name('schools.index');
        Route::get('/schools/create', [Admin\SchoolController::class, 'create'])->name('schools.create');
        Route::post('/schools', [Admin\SchoolController::class, 'store'])->name('schools.store');
        Route::get('/schools/{school}/edit', [Admin\SchoolController::class, 'edit'])->name('schools.edit');
        Route::put('/schools/{school}', [Admin\SchoolController::class, 'update'])->name('schools.update');
        Route::delete('/schools/{school}', [Admin\SchoolController::class, 'destroy'])->name('schools.destroy');

        // Users (siswa/orang tua)
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/bulk-create', [Admin\UserController::class, 'bulkCreate'])->name('users.bulk-create');
        Route::post('/users/bulk-upload', [Admin\UserController::class, 'bulkUpload'])->name('users.bulk-upload');
        Route::get('/users/bulk-template', [Admin\UserController::class, 'downloadTemplate'])->name('users.bulk-template');
        Route::post('/users/bulk', [Admin\UserController::class, 'bulkStore'])->name('users.bulk');
        Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Suggestions
        Route::get('/suggestions', [Admin\SuggestionController::class, 'index'])->name('suggestions.index');
        Route::patch('/suggestions/{suggestion}/mark-read', [Admin\SuggestionController::class, 'markRead'])->name('suggestions.mark-read');
        Route::post('/suggestions/mark-all-read', [Admin\SuggestionController::class, 'markAllRead'])->name('suggestions.mark-all-read');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Audit Logs
        Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Sekolah)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Complaints (admin sekolah bisa lihat dan buat aduan)
        Route::get('/complaints', [Admin\ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create', [Admin\ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [Admin\ComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}', [Admin\ComplaintController::class, 'show'])->name('complaints.show');

        // Categories (admin sekolah hanya bisa lihat, tidak bisa CRUD)
        Route::get('/categories', [Admin\CategoryController::class, 'index'])->name('categories.index');

        // Schools (admin sekolah hanya bisa lihat)
        Route::get('/schools', [Admin\SchoolController::class, 'index'])->name('schools.index');

        // Users (siswa/orang tua only - admin sekolah bisa kelola user di sekolahnya)
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/bulk-create', [Admin\UserController::class, 'bulkCreate'])->name('users.bulk-create');
        Route::post('/users/bulk-upload', [Admin\UserController::class, 'bulkUpload'])->name('users.bulk-upload');
        Route::get('/users/bulk-template', [Admin\UserController::class, 'downloadTemplate'])->name('users.bulk-template');
        Route::post('/users/bulk', [Admin\UserController::class, 'bulkStore'])->name('users.bulk');
        Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Suggestions (admin sekolah bisa lihat dan kirim saran)
        Route::get('/suggestions', [Admin\SuggestionController::class, 'index'])->name('suggestions.index');
        Route::get('/suggestions/create', [Admin\SuggestionController::class, 'create'])->name('suggestions.create');
        Route::post('/suggestions', [Admin\SuggestionController::class, 'store'])->name('suggestions.store');
        Route::patch('/suggestions/{suggestion}/mark-read', [Admin\SuggestionController::class, 'markRead'])->name('suggestions.mark-read');
        Route::post('/suggestions/mark-all-read', [Admin\SuggestionController::class, 'markAllRead'])->name('suggestions.mark-all-read');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // Audit Logs
        Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    });

/*
|--------------------------------------------------------------------------
| User (Siswa/Orang Tua) Routes
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');

        // Complaints
        Route::get('/complaints', [User\ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create', [User\ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [User\ComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}', [User\ComplaintController::class, 'show'])->name('complaints.show');
        Route::get('/complaints/{complaint}/edit', [User\ComplaintController::class, 'edit'])->name('complaints.edit');
        Route::put('/complaints/{complaint}', [User\ComplaintController::class, 'update'])->name('complaints.update');
        Route::delete('/complaints/{complaint}', [User\ComplaintController::class, 'destroy'])->name('complaints.destroy');

        // Suggestions
        Route::get('/suggestions', [User\SuggestionController::class, 'index'])->name('suggestions.index');
        Route::get('/suggestions/create', [User\SuggestionController::class, 'create'])->name('suggestions.create');
        Route::post('/suggestions', [User\SuggestionController::class, 'store'])->name('suggestions.store');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    });
