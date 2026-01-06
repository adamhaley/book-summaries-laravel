<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('user:make-admin {email}', function (string $email) {
    $user = User::query()->where('email', $email)->first();

    if (! $user) {
        $this->error("No user found with email: {$email}");

        return 1;
    }

    $profile = $user->profile()->firstOrNew();
    $profile->role = 'admin';

    if (! is_array($profile->preferences)) {
        $profile->preferences = [
            'style' => 'narrative',
            'length' => '5pg',
        ];
    }

    $profile->save();

    $this->info("User promoted to admin for Filament access: {$email}");

    return 0;
})->purpose('Grant Filament admin access to a user by email');
