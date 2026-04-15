<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ─────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

// Listings catalog
Route::prefix('listings')->name('listings.')->group(function () {
    Route::get('/', [ListingController::class, 'index'])->name('index');
    Route::get('/{listing:slug}', [ListingController::class, 'show'])->name('show');
});

// Legacy-friendly routes for specific listing types
Route::get('/sell-business', [ListingController::class, 'sellBusiness'])->name('sell-business');
Route::get('/buy-business', [ListingController::class, 'buyBusiness'])->name('buy-business');
Route::get('/investments', [ListingController::class, 'investments'])->name('investments');
Route::get('/franchises', [ListingController::class, 'franchises'])->name('franchises');

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
});

// Static pages (about, terms, privacy, etc.)
Route::get('/page/{page:slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Public profiles
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.public');

// ─── Auth Routes ────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated Routes ────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // Listing Management
    Route::prefix('my-listings')->name('my-listings.')->group(function () {
        Route::get('/', [ListingController::class, 'myListings'])->name('index');
        Route::get('/create', [ListingController::class, 'create'])->name('create');
        Route::post('/', [ListingController::class, 'store'])->name('store');
        Route::get('/{listing:slug}/edit', [ListingController::class, 'edit'])->name('edit');
        Route::put('/{listing:slug}', [ListingController::class, 'update'])->name('update');
        Route::delete('/{listing:slug}', [ListingController::class, 'destroy'])->name('destroy');
        Route::post('/{listing:slug}/publish', [ListingController::class, 'publish'])->name('publish');
        Route::post('/{listing:slug}/archive', [ListingController::class, 'archive'])->name('archive');
    });

    // Favorites
    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
        Route::post('/{listing}', [FavoriteController::class, 'toggle'])->name('toggle');
    });

    // Conversations & Messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::post('/{listing}/start', [ConversationController::class, 'start'])->name('start');
        Route::post('/{conversation}/reply', [ConversationController::class, 'reply'])->name('reply');
    });
});

// ─── API-like AJAX Routes ────────────────────────────────────────────────────

Route::middleware('auth')->prefix('api/v1')->name('api.')->group(function () {
    Route::post('/listings/{listing}/view', [ListingController::class, 'trackView'])->name('listings.view');
    Route::post('/listings/{listing}/favorite', [FavoriteController::class, 'toggle'])->name('listings.favorite');
});
