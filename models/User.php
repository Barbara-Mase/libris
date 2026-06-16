<?php

class User {

    // DateTimeUmmitable ne peut pas être modifié
    private DateTime $registration_date;
    private DateTime $lastLogin;

    private string $role = 'USER';

    public function __construct(private string $username, private string $email, private string $password, private string $intro, private ?int $id = null)
    {
    }

    public function getId() : int {
        return $this->id;
    }

    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function setUsername(string $username) : void
    {
        $this->username = $username;
    }

    public function getEmail() : string {
        return $this->email;
    }
    public function setEmail(string $email) : void {
        $this->email = $email;
    }
    public function getPassword() : string {
        return $this->password;
    }

    public function setPassword(string $password) : void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getIntro(): string
    {
        return $this->intro;
    }

    public function setIntro(string $intro) : void
    {
        $this->intro = $intro;
    }

    public function getRegistrationDate() : DateTime {
        return $this->registration_date;
    }

    public function setRegistrationDate(DateTime $registration_date) : void {
        $this->registration_date = $registration_date;
    }

    public function getLastLogin() : DateTime {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $lastLogin) : void {
        $this->lastLogin = $lastLogin;
    }

    public function getRole() : string {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}