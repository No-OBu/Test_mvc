<?php
namespace Application;

class dbConnection
{
    private $co = null;
    private $isConnected = false;
    private $dsn = '';
    private $user = '';
    private $password = '';

    public function __construct($db, $host, $user, $password)
    {
        $this->dsn = sprintf('mysql:host=%s;dbname=%s', $host, $db);
        $this->user = $user;
        $this->password = $password;
        $this->connect();
    }

    protected function connect()
    {
        if (!$this->isConnected) {
            $this->co = new \PDO($this->dsn, $this->user, $this->password);
            $this->isConnected = true;
        }
    }

    public function disconnect()
    {
        if ($this->isConnected) {
            $this->isConnected = false;
            $this->co = null;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function getConnection()
    {
        return $this->co;
    }
}
