<?php

class User {
    //ajouter registration_date
    public function __construct(private int $id, private string $username, private string $password)
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}