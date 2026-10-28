<?php

declare(strict_types=1);

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/aktuelles', [PostController::class, 'index'])->name('posts.index');
Route::get('/aktuelles/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/termine', [EventController::class, 'index'])->name('events.index');

Route::get('/galerie', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/galerie/{slug}', [AlbumController::class, 'show'])->name('albums.show');

Route::get('/downloads', [PageController::class, 'downloads'])->name('downloads.index');
Route::get('/kontakt', [PageController::class, 'contact'])->name('contact');
Route::get('/impressum', [PageController::class, 'impressum'])->name('impressum');
Route::get('/datenschutz', [PageController::class, 'datenschutz'])->name('datenschutz');

Route::get('/{section}/{slug}', [PageController::class, 'show'])
    ->whereIn('section', ['unsere-schule', 'schulleben'])
    ->name('pages.show');
