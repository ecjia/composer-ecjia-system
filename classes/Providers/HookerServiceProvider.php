<?php

namespace Ecjia\System\Providers;

use Royalcms\Component\Support\ServiceProvider;
use Royalcms\Component\Hook\Dispatcher;

class HookerServiceProvider extends ServiceProvider
{
    /**
     * The hook action listener mappings for the application.
     *
     * @var array
     */
    protected $actions = [
        'mail_init' => [
            'Ecjia\System\Hookers\AddMacroSendMailAction'
        ],

    ];

    /**
     * The hook filters listener mappings for the application.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * Register any other events for your application.
     *
     * @param  \Royalcms\Component\Hook\Dispatcher  $dispatcher
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {

        foreach ($this->actions as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->addAction($event, $listener);
            }
        }

        foreach ($this->filters as $event => $listeners) {
            foreach ($listeners as $listener) {
                $dispatcher->addFilter($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $dispatcher->subscribe($subscriber);
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
