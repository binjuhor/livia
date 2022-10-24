<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MailHandler;
use BeyondCode\Mailbox\Facades\Mailbox;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function boot(): void
    {
        Mailbox::catchAll(MailHandler::class);
    }
}
