<?php

namespace Notifications\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Notifications\Mail\AdminNoteCreatedMail;
use Notes\Events\NoteCreated;

class SendAdminNoteCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(NoteCreated $event): void
    {
        $adminEmail = config('mail.admin_recipient');
        
        if (!$adminEmail) {
            return;
        }

        Mail::to($adminEmail)->send(new AdminNoteCreatedMail($event->note));
    }
}

