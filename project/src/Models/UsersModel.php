<?php
namespace Models;

class UsersModel extends BaseModel
{
    protected $login;
    protected $password;

    public function __construct($dataArray)
    {
        parent::__construct($dataArray);

        if (isset($dataArray['login'])) {
            $this->setLogin($dataArray['login']);
        }
        if (isset($dataArray['password'])) {
            $this->setPassword($dataArray['password']);
        }
    }

    public function setLogin($login)
    {
        $this->login = strtolower($login);

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
