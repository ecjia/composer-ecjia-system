<?php


namespace Ecjia\System\Mixins;


use Ecjia\Component\Mailer\Mailer;
use RC_Hook;

class EcjiaMailMixin
{

    /**
     * @return \Closure
     */
    public function send_mail()
    {
        return function ($name, $email, $subject, $content, $type = 0, $notification = false) {

            //hook
            RC_Hook::add_action('reset_mail_config', ['Ecjia\Component\Mailer\Mailer', 'ecjia_mail_config']);

            return (new Mailer(
                config('mail.default'),
                $this->getViewFactory(),
                $this->getSwiftMailer(),
                royalcms('events')
            ))->send_mail($name, $email, $subject, $content, $type, $notification);
        };
    }

}