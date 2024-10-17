<?php

use App\Events\ContactRequestEvent;
use Illuminate\Events\Dispatcher;

class ContactEventSubscriber {


    public function subscribe(Dispatcher $dispatcher){
        $dispatcher->listen(
            ContactRequestEvent::class
        );
    }
}