<?php

namespace Ecjia\System\Providers;

class HookerServiceProvider
{
    /**
     * The hook action listener mappings for the application.
     *
     * @var array
     */
    protected $actions = [
//        'royalcms.query' => [
//            'Ecjia\System\Listeners\DatabaseQueryListener',
//        ],
//
//        'royalcms.warning.exception' => [
//            'Ecjia\System\Listeners\WarningExceptionListener',
//        ],
    ];

    /**
     * The hook filters listener mappings for the application.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Register any other events for your application.
     *
     * @param  \Royalcms\Component\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot($events)
    {

        foreach ($this->actions as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

        foreach ($this->filters as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function filters()
    {
        return $this->filters;
    }

}
