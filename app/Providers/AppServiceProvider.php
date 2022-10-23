<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MailHandler;
use BeyondCode\Mailbox\Facades\Mailbox;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Mailbox::catchAll(MailHandler::class);
        Mailbox::to('livia@mg.chillbits.com', MailHandler::class);
    }
}
