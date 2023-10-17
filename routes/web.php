<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\PostController as GuestPostController;
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

Route::get('/', function () {
    // return redirect("http://localhost:5174/");
    return view("guests.welcome");
});

Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Raggruppiamo le rotte
Route::middleware(['auth', 'verified'])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        // CREATE
        Route::get("/posts/create", [PostController::class, "create"])->name("posts.create");
        Route::post("/posts", [PostController::class, "store"])->name("posts.store");

        // READ
        Route::get("/posts", [PostController::class, "index"])->name("posts.index");
        Route::get("/posts/{post}", [PostController::class, "show"])->name("posts.show");

        // UPDATE
        Route::get("/posts/{post}/edit", [PostController::class, "edit"])->name("posts.edit");
        Route::patch("/posts/{post}", [PostController::class, "update"])->name("posts.update");

        // DELETE
        Route::delete("/posts/{post}", [PostController::class, "destroy"])->name("posts.destroy");
    });

Route::get("/posts", [GuestPostController::class, "index"])->name("posts.index");


Route::middleware('auth')->group(function () {
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/admin/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

require __DIR__ . '/auth.php';
