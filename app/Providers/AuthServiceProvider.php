<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Message;
use App\Models\Room;
use App\Models\Sensor;
use App\Policies\MessagePolicy;
use App\Policies\RoomPolicy;
use App\Policies\SensorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //

        Sensor::class => SensorPolicy::class,
        Room::class => RoomPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
