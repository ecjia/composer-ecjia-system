<?php


namespace Ecjia\System\Hookers;


use ecjia;

class ResetEcjiaMailConfigAction
{

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($config = [])
    {
        if (empty($config)) {
            $config = [
                'smtp_host'     => ecjia::config('smtp_host'),
                'smtp_port'     => ecjia::config('smtp_port'),
                'smtp_mail'     => ecjia::config('smtp_mail'),
                'shop_name'     => ecjia::config('shop_name'),
                'smtp_user'     => ecjia::config('smtp_user'),
                'smtp_pass'     => ecjia::config('smtp_pass'),
                'smtp_ssl'      => ecjia::config('smtp_ssl'),
                'mail_charset'  => ecjia::config('mail_charset'),
                'mail_service'  => ecjia::config('mail_service'),
            ];
        }

        if (empty($config['shop_name'])) {
            $config['shop_name'] = ecjia::config('shop_name');
        }

        royalcms('config')->set('mail.host',        array_get($config, 'smtp_host'));
        royalcms('config')->set('mail.port',        array_get($config, 'smtp_port'));
        royalcms('config')->set('mail.from.address', array_get($config, 'smtp_mail'));
        royalcms('config')->set('mail.from.name',   array_get($config, 'shop_name'));
        royalcms('config')->set('mail.username',    array_get($config, 'smtp_user'));
        royalcms('config')->set('mail.password',    array_get($config, 'smtp_pass'));
        royalcms('config')->set('mail.charset',     array_get($config, 'mail_charset'));

        if (intval(array_get($config, 'smtp_ssl')) === 1) {
            royalcms('config')->set('mail.encryption', 'ssl');
        } else {
            royalcms('config')->set('mail.encryption', 'tcp');
        }

        if (intval(array_get($config, 'mail_service')) === 1) {
            royalcms('config')->set('mail.driver', 'smtp');
        } else {
            royalcms('config')->set('mail.driver', 'mail');
        }
    }

}