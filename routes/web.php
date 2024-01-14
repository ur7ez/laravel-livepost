<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;

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
    return view('welcome');
});

Route::get('/app', function () {
    return view('app');
});


Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), function ($token) {
    return view('auth.password-reset', [
        'token' => $token,
    ]);
})
    ->middleware(['guest:' . config('fortify.guard')])
    ->name('password.reset');

if (App::environment('local')) {

    /*// App::setLocale('es');  // sets locale on App level
    // Lang::setLocale('es');  // sets locale on Facade level
    $trans = Lang::get('auth.failed');
    $trans = __('auth.throttle', ['seconds' => 5]);
    // current locale
    dump(App::currentLocale());
    dump(App::isLocale('en'));

    $trans = __('this is sparta');
    $trans = trans_choice('auth.pants', 6);
    $trans = trans_choice('auth.apples', 2, ['baskets' => 2]);
    $trans = __('auth.welcome', ['name' => 'sam']);

    dd($trans);*/

    Route::get('/playground', function () {
        $user = \App\Models\User::factory()->make();

        \Illuminate\Support\Facades\Mail::to($user)
            ->send(new \App\Mail\WelcomeMail($user));
        return response('Email sent OK');
    });
}
