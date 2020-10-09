<?php

namespace Ecjia\System\Admins\Users;

use Ecjia\System\Frameworks\Contracts\UserInterface;
use Royalcms\Component\Repository\Repositories\AbstractRepository;
use RC_Hook;
use RC_Uri;
use Royalcms\Component\Support\Traits\Macroable;

class AdminUser extends AbstractRepository implements UserInterface
{
    use Macroable;

    protected $model = 'Ecjia\System\Admins\Users\AdminUserModel';
    
    protected $user;
    
    /**
     * 
     * @var \Ecjia\System\Frameworks\Contracts\UserAllotPurview
     */
    protected $purview;
    
    public function __construct($userid, $purviewClass = null)
    {
        $this->model = RC_Hook::apply_filters('ecjia_admin_user_model', $this->model);
        
        parent::__construct();

        $this->user = $this->find($userid);
        
        if (is_string($purviewClass) && class_exists($purviewClass)) {
            $this->purview = new $purviewClass($userid);
        }
        elseif (is_callable($purviewClass)) {
            $this->purview = $purviewClass($userid);
        }
        elseif (is_null($purviewClass)) {
            $pruviewClass = config('ecjia.admin_user_purview', '\Ecjia\System\Admins\Users\AdminUserDefaultAllotPurview');
            $this->purview = new $pruviewClass($userid);
        }
        
        $this->purview = RC_Hook::apply_filters('ecjia_admin_user_allot_purview_handle', $this->purview);
    }
    
    /**
     * 获取用户名
     */
    public function getUserName()
    {
        return $this->user->user_name;
    }
    
    /**
     * 获取用户ID
     */
    public function getUserId()
    {
        return $this->user->user_id;
    }
    
    /**
     * 获取用户的类型
     */
    public function getUserType()
    {
        return 'admin';
    }
    
    /**
     * 获取用户邮箱
     */
    public function getEmail()
    {
        return $this->user->email;
    }
    
    /**
     * 获取用户最后一次登录时间
     */
    public function getLastLogin()
    {
        return $this->user->last_login;
    }
    
    /**
     * 获取用户最后一次登录IP
     */
    public function getLastIp()
    {
        return $this->user->last_ip;
    }
    
    /**
     * 获取用户权限列表
     */
    public function getActionList()
    {
        return $this->purview->get();
    }
    
    /**
     * 设置用户公众平台权限
     * @param string $purview
     * @return boolean
     */
    public function setActionList($purview)
    {
        return $this->purview->save($purview);
    }
    
    
    /**
     * 获取用户设置的语言类型
     */
    public function getLangType()
    {
        return $this->user->lang_type;
    }
    
    /**
     * 获取用户的角色ID
     */
    public function getRoleId()
    {
        return $this->user->role_id;
    }
    
    /**
     * 获取用户的类型
     */
    public function getAddTime()
    {
        return $this->user->add_time;
    }
    
}