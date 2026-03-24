<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
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

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/portfolio', [HomeController::class, 'portfolio'])->name('portfolio');
Route::get('/experience', [HomeController::class, 'experience'])->name('experience');
Route::get('/skills', [HomeController::class, 'skills'])->name('skills');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// SECURITY FIX: Apply rate limiting to contact form submission
Route::post('/contact', [HomeController::class, 'sendMessage'])
    ->name('contact.send')
    ->middleware('throttle:contact');

// Web3 integration routes
Route::get('/web3/wallet-connect', [HomeController::class, 'walletConnect'])->name('web3.wallet.connect');
Route::get('/web3/nft-gallery', [HomeController::class, 'nftGallery'])->name('web3.nft.gallery');

// SEO routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// SECURITY FIX: Apply rate limiting to login
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

// SECURITY: Registration is disabled - see AuthController
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (protected)
Route::middleware(['auth', 'admin', 'throttle:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Projects CRUD
    Route::resource('/projects', AdminController::class);
    
    // Experiences CRUD
    Route::resource('/experiences', AdminController::class);
    
    // Skills CRUD
    Route::resource('/skills', AdminController::class);
    
    // Contact messages
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::get('/messages/{message}', [AdminController::class, 'showMessage'])->name('admin.messages.show');
    Route::post('/messages/{message}/respond', [AdminController::class, 'respondMessage'])->name('admin.messages.respond');
    
    // Analytics
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
});
