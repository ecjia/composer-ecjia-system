<?php


namespace Ecjia\System\Listeners;


use ecjia;
use Ecjia\System\Events\AdminUserForgetPasswordEvent;
use Ecjia\System\Exceptions\SendMailFailedException;
use ecjia_admin;
use ecjia_password;
use RC_Api;
use RC_Mail;
use RC_Time;
use RC_Uri;

class AdminUserForgetPasswordSendMailListener
{

    /**
     * 处理事件
     *
     * @param AdminUserForgetPasswordEvent $event
     * @return bool
     * @throws SendMailFailedException
     */
    public function handle(AdminUserForgetPasswordEvent $event)
    {
        $admin_model = $event->admin_model;

        /* 生成验证的code */
        $rand_code = str_random(10);

        $admin_model->setMeta('forget_password_hash', $rand_code);

        $admin_id   = $admin_model['user_id'];
        $admin_pass = $admin_model['password'];

        $pm = ecjia_password::autoCompatibleDriver($admin_pass);
        $code        = $pm->generateResetPasswordHash($admin_id, $admin_pass, $rand_code);

        $reset_email = RC_Uri::url('@get_password/reset_pwd_form', array('uid' => $admin_id, 'code' => $code));
        /* 设置重置邮件模板所需要的内容信息 */
        $template = RC_Api::api('mail', 'mail_template', 'send_password');

        $admin_email = $admin_model->email;
        $content = $this->renderMailContent($admin_model, $reset_email, $template['template_content']);

        if (RC_Mail::send_mail('', $admin_email, $template['template_subject'], $content, $template['is_html'])) {
            return true;
        } else {
            throw new SendMailFailedException(__('重置密码邮件发送失败!请与管理员联系'));
        }
    }

    /**
     * @param $admin_model
     * @param $reset_email
     * @param $template_content
     * @return string
     */
    private function renderMailContent($admin_model, $reset_email, $template_content)
    {
        ecjia_admin::$controller->assign('user_name', $admin_model->user_name);
        ecjia_admin::$controller->assign('reset_email', $reset_email);
        ecjia_admin::$controller->assign('shop_name', ecjia::config('shop_name'));
        ecjia_admin::$controller->assign('send_date', RC_Time::local_date(ecjia::config('date_format')));
        ecjia_admin::$controller->assign('sent_date', RC_Time::local_date(ecjia::config('date_format')));

        $content = ecjia_admin::$controller->fetch_string($template_content);

        return $content;
    }


}