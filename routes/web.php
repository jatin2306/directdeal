<?php
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Models\Property;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserListingController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/update-price/{id}/{price}', function () {
    $property = Property::findOrFail(36);
    $property->price = 10000;
    $property->save();

    return 'Price updated successfully!';
});
Route::get('/test-log', function () {
    Log::error('This is a test error log.');
    return 'Check storage/logs/laravel.log';
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('setLocale');

Route::get('/about', function () {
    return view('about');
});
Route::get('/services', function () {
    return view('services');
})->name('services');
Route::get('/advertising', function () {
    return view('advertising');
});
Route::get('/contact', function () {
    return view('contact');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::post('/toggle-favorite/{property}', [FavoriteController::class, 'toggleFavorite'])->name('toggleFavorite')->middleware('auth');

Route::get('/properties', [PropertyController::class, 'index'])->name('property.index'); // List properties
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('property.show'); // Property details

Route::get('/submit-property', function () {
    return view('submit-property');
})->middleware(['auth', 'verified'])->name('submit.property');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'edit'])->name('dashboard');
    // My Properties
    Route::get('/dashboard/my-properties', [ProfileController::class, 'myProperties'])->name('properties.my');
    Route::get('/dashboard/saved-properties', [ProfileController::class, 'savedProperties'])->name('properties.saved');
    Route::get('/dashboard/transactions', [ProfileController::class, 'myTransactions'])->name('user.transactions');

    //Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('property.edit');
    //Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/property/store', [PropertyController::class, 'store'])->name('property.store');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/subscribe/price-drop/{property}', [NotificationController::class, 'subscribeForPriceDrop'])->name('subscribe.priceDrop');
    Route::middleware(['auth'])->get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

require __DIR__.'/auth.php';

require __DIR__.'/admin-auth.php';


use App\Http\Controllers\Auth\PasswordResetLinkController;

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/add-listing', [UserListingController::class, 'create'])
//         ->name('add.listing');

//     Route::post('/add-listing', [UserListingController::class, 'store'])
//         ->name('add.listing.store');
// });

// Route::middleware(['auth'])->get('/add-listing', function () {
//     return 'ADD LISTING AUTH ROUTE WORKS';
// });

// Route::middleware(['auth'])->get('/add-listing', [UserListingController::class, 'create']);


Route::middleware(['auth'])->group(function () {

    Route::get('/add-listing', [UserListingController::class, 'create'])
        ->name('add.listing');

    Route::post('/add-listing', [UserListingController::class, 'store'])
        ->name('add.listing.store');

    Route::get('/my-listings', [UserListingController::class, 'index'])
        ->name('my.listings');

});