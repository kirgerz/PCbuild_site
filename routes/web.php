<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuildController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Главная лента сборок ПК и фильтрация по категориям
Route::get('/', [BuildController::class, 'index'])->name('home');
Route::get('/category/{category:slug}', [BuildController::class, 'index'])->name('category');

// Маршруты для гостей (вход и регистрация)
Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// Маршруты для всех авторизованных пользователей (профиль, комментарии, выход)
Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Личный профиль (смена никнейма, описания и список своих билдов у сборщика)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Добавление и удаление комментариев под сборками
    Route::post('/builds/{build}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Режим конфигуратора ПК (доступен только Сборщику и Администратору)
    Route::middleware('role:Сборщик,Администратор')->group(function (): void {
        Route::get('/builder', [BuildController::class, 'create'])->name('builder');
        Route::post('/builds', [BuildController::class, 'store'])->name('builds.store');
        Route::get('/builder/{build}/edit', [BuildController::class, 'edit'])->name('builds.edit');
        Route::put('/builds/{build}', [BuildController::class, 'update'])->name('builds.update');
    });

    // Админ-панель (доступна только Администратору)
    Route::middleware('role:Администратор')->group(function (): void {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin');
        Route::patch('/admin/builds/{build}/block', [AdminController::class, 'toggleBlock'])->name('admin.builds.block');
        Route::delete('/admin/builds/{build}', [AdminController::class, 'destroyBuild'])->name('admin.builds.destroy');
        Route::patch('/admin/users/role', [AdminController::class, 'updateRole'])->name('admin.users.role');
        Route::post('/admin/components', [AdminController::class, 'storeComponent'])->name('admin.components.store');
    });
});
