<?php

namespace App\Listeners;

use Illuminate\Mail\Mailer;
use App\Mail\PropertyContactMail;
use App\Events\ContactRequestEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactListener 
{
    /**
     * Create the event listener.
     */
    public function __construct(private Mailer $mailer)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ContactRequestEvent $event): void
    {
     $this->mailer->send(new PropertyContactMail($event->property,$event->data));
        
    }
}
