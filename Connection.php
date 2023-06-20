<?php

class Connection
{
    private $host = 'db';
    private $database = 'myDatabase';

    private $username = 'user';
    private $password = 'password';
    private $connection;

    public function __construct()
    {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }


}

?>