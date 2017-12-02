<?php
namespace Controllers;

use SQLData\UsersSQLData;
use SQLData\MessagesSQLData;

class ChatController extends BaseController
{
    public function homeAction()
    {
        $session = $this->getSession();
        if ($session->isAuthenticated()) {
            return $this->redirect('chat_route');
        }

        return $this->redirect('login_route');
    }

    public function logoutAction()
    {
        $session = $this->getSession();
        $session->logout();

        return $this->redirect('login_route');
    }

    public function loginAction()
    {
        $session = $this->getSession();
        if ($session->isAuthenticated()) {
            return $this->redirect('chat_route');
        }

        $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
        if ('POST' === $method) {
            $this->checkLoginForm($session);
        }

        $this->addParameter('router', $this->getRouter());
        $this->addParameter('csrf_token', $session->createCSRFToken());
        $this->addParameter('errors', $session->getErrors());

        return $this->renderView(dirname(__DIR__).'/Views/login.phtml');
    }

    protected function checkLoginForm($session)
    {
        $session_token = $session->get('token');
        $csrf_token = filter_input(INPUT_POST, "token", FILTER_SANITIZE_STRING);
        $login = strtolower(filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING));
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

        if ($this->checkCSRF($session_token, $csrf_token)) {
            $userSQLData = new UsersSQLData($this->getDb());
            $user = $userSQLData->findOneBy(['login' => $login]);
            if ((!is_null($user)) && password_verify($password, $user->getPassword())) {
                $session->authenticateUser($user);

                return $this->redirect('chat_route');
            } elseif (is_null($user)) {
                $dataUser['login'] = $login;
                $dataUser['password'] = password_hash($password, PASSWORD_BCRYPT);
                $user = $userSQLData->createObject($dataUser);
                if ($id = $userSQLData->saveObject($user)) {
                    $user->setId($id);
                    $session->authenticateUser($user);

                    return $this->redirect('chat_route');
                } else {
                    $session->addError('Error : User can not be saved');
                }
            } else {
                $session->addError('Error : Bad password');
            }
        } else {
            $session->addError('Error : Invalid CSRF');
        }

        return $this->redirect('login_route');
    }

    public function chatAction()
    {
        $session = $this->getSession();
        if (!$session->isAuthenticated()) {
            return $this->redirect('login_route');
        }
        $messagesSQLData = new MessagesSQLData($this->getDb());
        $userSQLData = new UsersSQLData($this->getDb());

        $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
        if ('POST' === $method) {
            $this->checkChatForm($session, $messagesSQLData);
        }

        $messages = $messagesSQLData->findAll();
        foreach ($messages as $message) {
            $message->setAuthorModel($userSQLData->findOne($message->getAuthor()));
        }

        $this->addParameter('router', $this->getRouter());
        $this->addParameter('csrf_token', $session->createCSRFToken());
        $this->addParameter('messages', $messages);
        $this->addParameter('errors', $session->getErrors());

        return $this->renderView(dirname(__DIR__).'/Views/chat.phtml');
    }

    protected function checkChatForm($session, $messagesSQLData)
    {
        $session_token = $session->get('token');
        $csrf_token = filter_input(INPUT_POST, "token", FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!empty($message)) {
            if ($this->checkCSRF($session_token, $csrf_token)) {
                $dataMessage['author'] = $session->get('user_id');
                $dataMessage['content'] = $message;
                $dataMessage['date'] = new \DateTime();
                $message = $messagesSQLData->createObject($dataMessage);
                if (!$messagesSQLData->saveObject($message)) {
                    $session->addError('Error : Message can not be saved');
                }
            } else {
                $session->addError('Error : Invalid CSRF');
            }
        }

        return $this->redirect('chat_route');
    }
}
