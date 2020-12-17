<?php


namespace Ecjia\System\Mailables;


use ecjia;
use Ecjia\App\Mail\Mailable\MailableAbstract;
use Ecjia\System\Admins\Users\AdminUserModel;
use Ecjia\System\Frameworks\Contracts\AdminUserModelInterface;
use ecjia_error;
use ecjia_password;
use RC_Time;
use RC_Uri;

class AdminUserForgetPassword extends MailableAbstract
{

    protected $eventCode = 'send_password';

    /**
     * 订单实例。
     *
     * @var AdminUserModel
     */
    public $adminModel;

    /**
     * 创建一个消息实例。
     *
     * @return void
     */
    public function __construct($admin_model)
    {
        parent::__construct();

        $this->adminModel = $admin_model;

    }

    /**
     * 构造消息。
     *
     * @return ecjia_error|string
     */
    public function build()
    {
        if (empty($this->templateModel)) {
            return new ecjia_error('mail_template_not_exist', __('邮件模板不存在', 'mail'));
        }

        $content = $this->templateModel->template_content;
        $reset_email = $this->getResetMailUrl();
//dd($content);
//        $view = $this->createViewEngine();
//        $view->assign('user_name', );
//        $view->assign('reset_email', );
//        $view->assign('shop_name', );
//        $view->assign('send_date', );

        $data = [
            'user_name' => $this->adminModel->user_name,
            'reset_email' => $reset_email,
            'shop_name' => ecjia::config('shop_name'),
            'send_date' => RC_Time::local_date(ecjia::config('date_format')),
        ];
//dd(1);
        $this->view($content, $data);

//        $this->renderContent = $view->fetch('string:' . $content);
//        $this->html($this->renderContent);
//        dd($this->renderContent);
    }

    private function getResetMailUrl()
    {
        /* 生成验证的code */
        $rand_code = str_random(10);

        $this->adminModel->setMeta('forget_password_hash', $rand_code);

        $admin_id   = $this->adminModel->user_id;
        $admin_pass = $this->adminModel->password;

        $pm = ecjia_password::autoCompatibleDriver($admin_pass);
        $code        = $pm->generateResetPasswordHash($admin_id, $admin_pass, $rand_code);

        $reset_email = RC_Uri::url('@get_password/reset_pwd_form', array('uid' => $admin_id, 'code' => $code));

        return $reset_email;
    }



}