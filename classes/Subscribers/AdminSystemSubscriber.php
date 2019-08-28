<?php


namespace Ecjia\System\Subscribers;

use Royalcms\Component\Hook\Dispatcher;

class AdminSystemSubscriber
{

    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {

    }


    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {

    }


    /**
     * Register the listeners for the subscriber.
     *
     * @param \Royalcms\Component\Hook\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
//        $events->addAction(
//            'App\Events\UserLoggedIn',
//            'App\Listeners\UserEventListener@onUserLogin'
//        );
//
//        $events->addAction(
//            'App\Events\UserLoggedOut',
//            'App\Listeners\UserEventListener@onUserLogout'
//        );
    }

}