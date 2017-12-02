<?php
namespace Application;

use Models\UsersModel;

class Session
{
    protected $isStarted = false;

    public function __construct($options = [])
    {
        if (session_start($options)) {
            $this->isStarted = true;
        } else {
            throw new Exception("Session can not be start", 500);
        }

        if (empty($_SESSION)) {
            $this->initSession();
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if ($this->isStarted) {
            $this->isStarted = false;
            session_write_close();
        }

        return $this;
    }

    public function authenticateUser(UsersModel $user)
    {
        session_regenerate_id(true);
        $_SESSION = array();
        $_SESSION['isAuthenticated'] = true;
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['login'] = $user->getLogin();
        $_SESSION['errors'] = [];
        $_SESSION['token'] = '';

        return $this;
    }

    public function logout()
    {
        session_regenerate_id(true);
        $this->initSession();

        return $this;
    }

    public function isAuthenticated()
    {
        if (isset($_SESSION['isAuthenticated'])) {
            return $_SESSION['isAuthenticated'];
        }

        return false;
    }

    public function getAuthenticateUser()
    {
        if ($this->isAuthenticated()) {
            $data = [
        'id' => $_SESSION['user_id'],
        'login' => $_SESSION['login'],
      ];

            return new UserModel($data);
        }

        return null;
    }

    protected function initSession()
    {
        $_SESSION = array();
        $_SESSION['isAuthenticated'] = false;
        $_SESSION['user_id'] = -1;
        $_SESSION['login'] = 'Anonymous';
        $_SESSION['errors'] = [];
        $_SESSION['token'] = '';

        return $this;
    }


    public function createCSRFToken()
    {
        $token = base64_encode(openssl_random_pseudo_bytes(32));
        $_SESSION['token'] = $token;

        return $token;
    }

    public function get($name)
    {
        return $_SESSION[$name];
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;

        return $this;
    }

    public function addError($error)
    {
        $_SESSION['errors'][] = $error;
    }

    public function getErrors()
    {
        $errors = $_SESSION['errors'];
        $_SESSION['errors'] = [];

        return $errors;
    }
}
