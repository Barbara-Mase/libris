<?php

class User {
    //ajouter registration_date
    public function __construct(private int $id, private string $username, private string $email, private string $password)
    {
    }

    /**
     * @return int
     */
    public function getId() : int {
        return $this->id;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function getEmail() : string {
        return $this->email;
    }
    public function getPassword() : string {
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

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}