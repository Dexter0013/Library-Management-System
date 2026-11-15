<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::get('/init-db', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        $migrate = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seed = \Illuminate\Support\Facades\Artisan::output();

        return response("<h2>Database Initialized Successfully!</h2><pre>{$migrate}\n{$seed}</pre><br><a href='/login'>Proceed to Login</a>");
    } catch (\Throwable $e) {
        $prev = $e->getPrevious() ? "\nPrevious: " . $e->getPrevious()->getMessage() : "";
        return response("<h2>Database Initialization Failed:</h2><pre>{$e->getMessage()}{$prev}\n\nTrace:\n{$e->getTraceAsString()}</pre>", 500);
    }
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/my-borrows', [BorrowController::class, 'index'])->name('borrows.index');
    Route::post('/books/{book}/borrow', [BorrowController::class, 'store'])->name('borrows.store');
    Route::post('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('borrows.return');

    Route::middleware('admin')->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

        Route::get('/admin/borrows', [BorrowController::class, 'adminIndex'])->name('admin.borrows.index');

        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::patch('/admin/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('admin.users.toggle-role');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::resource('books', BookController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
});
