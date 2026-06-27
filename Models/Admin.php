<?php

class Admin
{
    private $conn;
    private $table = "admin";

    public function __construct($db)
    {
        $this->conn = $db;
    }
/*
    public function login($username, $password)
    {
        $sql = "SELECT * FROM ".$this->table." WHERE username = :username";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':username', $username);

        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // Plain-text password comparison
            if($password == $admin['password'])
            {
                return $admin;
            }
        }

        return false;
    }
   */
    public function login($username, $password)
    {
        $sql = "SELECT * FROM ".$this->table." WHERE username = :username";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $admin['password'])) {
                return $admin;
            }
        }

        return false;
    }
}
?>