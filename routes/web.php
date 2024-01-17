<?php

use App\Events\ChatMessageEvent;
use App\Models\Post;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\RoutePath;
use Illuminate\Http\Request;

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

Route::get('/shared/posts/{post}', function (Request $request, Post $post) {
    return "Specially made just for you 💕 ;) Post id: {$post->id}";
})
    ->name('shared.post')
    ->middleware('signed');

if (App::environment('local')) {
    Route::get('/shared/videos/{video}', function (Request $request, $video) {
        return 'git gud';
    })
        ->name('share-video')
        ->middleware('signed');

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
        return null;
//        return URL::temporarySignedRoute('share-video', now()->addSeconds(30), [
//            'video' => 123
//        ]);
//        $user = \App\Models\User::factory()->make();
//        \Illuminate\Support\Facades\Mail::to($user)
//            ->send(new \App\Mail\WelcomeMail($user));
//        return response('Email sent OK');
    });
    Route::get('/ws', function () {
        return view('websocket');
    });

    Route::post('/chat-message', function (Request $request) {
        event(new ChatMessageEvent($request->message, auth()->user()));
        return null;
    });
}
