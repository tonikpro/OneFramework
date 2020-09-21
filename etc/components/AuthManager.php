<?php
namespace etc\components;
use etc\app\App;

class AuthManager
{
    private $permissions;
    private $user;
    private $loginPage;
    private $roles = [];
    private $companies = [];
    public $isGuest = true;

    //todo нужно добавить функционал по загрузке правил для default_role
    public function __construct($config)
    {
        $this->loginPage = $config['login_page'];
        $roles = [];
        if(isset($config['default_role'])){
            $roles[] = $config['default_role'];
        }
        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT login, fullname, email, created_at, timezone FROM \"user\" WHERE user_id = :user_id";
            $this->user = App::i()->db->one($sql, ['user_id' => $user_id]);
            if($this->user != null){
                $sql = "SELECT role FROM users_roles WHERE user_id = :user_id";
                $roles = App::i()->db->all($sql, ['user_id' => $user_id]);
                foreach($roles as $role)
                {
                    $this->roles[] = $role->role;
                }
                $sql = "SELECT 
                            rp.permission 
                        FROM \"user\" u 
                        INNER JOIN users_roles ur ON ur.user_id = u.user_id 
                        INNER JOIN roles_permissions rp ON rp.role = ur.role 
                        WHERE u.user_id=:user_id";
                $permissions = App::i()->db->all($sql, ['user_id' => $user_id]);
                foreach($permissions as $p)
                {
                    $this->permissions[$p->permission] = $p->permission;
                }
                $this->permissions['@'] = '@';
                $sql = "SELECT company_id FROM users_companies WHERE user_id = :user_id";
                $companies = App::i()->db->all($sql, ['user_id' => $user_id]);
                foreach($companies as $c)
                {
                    $this->companies[$c->company_id] = $c->company_id;
                }
                $this->isGuest = false;
                $sql = 'SET timezone = \''.$this->user->timezone.'\'';
                App::i()->db->execute($sql);
            }
        }
    }

    public function can($permission)
    {
        return isset($this->permissions[$permission]);  
    }

    public function permissions()
    {
        return $this->permissions;
    }

    public function companies()
    {
        return $this->companies;
    }

    public function user()
    {
        return $this->user;
    }

    public function allow($permission)
    {
        if($this->isGuest){
            return App::i()->redirect($this->loginPage);
        }
        if(!$this->can($permission)){
            return App::i()->response(403, 'Forbidden');
        }
    }
}