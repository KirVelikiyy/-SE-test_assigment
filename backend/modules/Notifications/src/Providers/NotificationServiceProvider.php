<?php

namespace Notifications\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\View;
use Notifications\Listeners\SendAdminNoteCreatedNotification;
use Notes\Events\NoteCreated;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        NoteCreated::class => [
            SendAdminNoteCreatedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        View::addNamespace('notifications', resource_path('views/notifications'));
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

