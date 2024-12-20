<?php

namespace App\Providers;

use App\Models\ProductElectronics;
use App\Models\ProductRentHouse;
use App\Models\ProductVehicle;
use App\Observers\ProductElectronicObserver;
use App\Observers\ProductVehicleObserver;
use App\Observers\ProductRentHouseObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        ProductRentHouse::observe(ProductRentHouseObserver::class);
        ProductElectronics::observe(ProductElectronicObserver::class);
        ProductVehicle::observe(ProductVehicleObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
