<?php namespace Genetsis\Promotions\Events;


class PromoUserNotification
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
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(PromoUserCreated $event)
    {
        // Access the order using $event->order...
        echo 'adsfasdf';
        var_dump($event);
    }
}
