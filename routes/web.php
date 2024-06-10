<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', function (Request $request) {
    return Socialite::driver('discord')->redirect();
});

Route::get('/auth/callback', function (Request $request) {
    $discord = Socialite::driver('discord')->user();

    $user = User::query()->firstOrCreate([
        'discord_id' => $discord->getId(),
    ], [
        'username' => $discord->getNickname(),
        'email' => $discord->getEmail(),
        'avatar_url' => $discord->getAvatar(),
    ]);

    Auth::login($user, true);

    return redirect()->to('/');
});
