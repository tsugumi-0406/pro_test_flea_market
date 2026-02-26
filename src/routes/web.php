<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index']);

Route::get('/item/{item_id}', [ItemController::class, 'detail'])->name('item.detail');

Route::get('/search', [ItemController::class, 'search']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('auth')->group(function () {
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('item.purchase');

    Route::get('/purchase/address/{item_id}', [UserController::class, 'address'])->name('item.address');

    Route::post('/update/address/{item_id}', [UserController::class, 'updateAddress'])->name('item.updateAddress');

    Route::get('/sell', [ItemController::class, 'sell']);

    Route::get('/mypage', [UserController::class, 'mypage']);

    Route::get('/mypage/profile', [UserController::class, 'profile'])
    ->middleware(['auth', 'verified']);

    Route::post('/listing', [ItemController::class, 'listing']);

    Route::post('/profile', [UserController::class, 'update']);

    Route::post('/order', [ItemController::class, 'order']);

    Route::post('/checkout/session', [ItemController::class, 'checkout'])->name('checkout.session');

    Route::get('/payment/success', function () {
        return '決済完了しました';
    })->name('payment.success');

    Route::get('/payment/cancel', function () {
        return '決済がキャンセルされました';
    })->name('payment.cancel');

    Route::post('/comment', [ItemController::class, 'comment']);

    Route::post('/items/{item_id}/like', [ItemController::class, 'like'])->name('like');
});

