<?php

namespace App\Listeners;

use App\Events\CommentedEvent;
use App\Mail\Commented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class CommentedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CommentedEvent $event)
    {
        Mail::to($event->comment->user->email)->send(new Commented($event->comment));
    }
}
