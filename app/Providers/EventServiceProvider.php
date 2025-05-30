<?php

namespace App\Providers;

use Illuminate\Auth\Events\Authenticated;
use App\Listeners\RedirectAfterLogin;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
     /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Authenticated::class => [
            RedirectAfterLogin::class,  // Ensure listener is here
        ],
    ];
}
