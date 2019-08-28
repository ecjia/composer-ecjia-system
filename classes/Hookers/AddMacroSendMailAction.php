<?php


namespace Ecjia\System\Hookers;


use Ecjia\System\Frameworks\Component\Mailer;
use RC_Mail;

class AddMacroSendMailAction
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
     * @return void
     */
    public function handle()
    {
        RC_Mail::macro('send_mail', function ($name, $email, $subject, $content, $type = 0, $notification = false) {
            return with(new Mailer($this))->send_mail($name, $email, $subject, $content, $type, $notification);
        });

    }

}