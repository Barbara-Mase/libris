<?php

class User {

    private ? int $id;
    private DateTime $createdAt;
    //ajouter registration_date
    public function __construct(private string $username, private string $email, private string $password, private string $intro)
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
     * @return string
     */
    public function getIntro(): string
    {
        return $this->intro;
    }

    public function getCreatedAt() : DateTime {
        return $this->registration_date;
    }

    /**
     * @param int $id
     */
    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function setUsername(string $username) : void
    {
        $this->username = $username;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }
    public function setIntro(string $intro) : void
    {
        $this->intro = $intro;
    }

    public function setCreatedAt(DateTime $createdAt) : void {
        $this->createdAt = $createdAt;
    }
}