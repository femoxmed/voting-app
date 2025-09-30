<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        Route::aliasMiddleware('shareholder', \App\Http\Middleware\ShareholderMiddleware::class);

        // Define a custom mail transport with rate limiting
        Mail::extend('throttled_smtp', function () {
            $config = config('mail.mailers.smtp');

            $transport = new EsmtpTransport(
                $config['host'],
                $config['port'],
                ($config['encryption'] ?? null) === 'ssl'
            );

            $transport->setUsername($config['username']);
            $transport->setPassword($config['password']);

            // Set the rate limit: 3 emails per second (180 per minute)
            $transport->setMaxPerSecond(1/3);

            return $transport;
        });
    }
}
