<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Guests\PostController as GuestPostController;
use App\Http\Controllers\ProfileController;
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

Route::get("/posts", [GuestPostController::class, "index"])->name("guests.post.index");

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('prifle.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix("admin")                                                                                                        //tutte le rotte ora iniziano con "/admin"
    ->name("admin.")                                                                                                        //tutti i "->name" inizieranno con "admin."
    ->group(function () {                                                                                                    //raggruppa le rotte degli utenti loggati
        Route::get("/posts/create", [PostController::class, "create"])->name("posts.create");
        Route::get("/posts", [PostController::class, "store"])->name("posts.store");

        Route::get("/posts", [PostController::class, "index"])->name("posts.index");
        Route::get("/posts/{post}", [PostController::class, "show"])->name("posts.show");
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
