<?php

class UserManager extends AbstractManager {

    public function findAll() : array {

        $query = $this->db->prepare("SELECT * FROM users ORDER BY username");

        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($results as $result) {
            $user = new User($result['username'], $result['email'], $result['password'], $result['intro']);
            $id = intval($result['user_id']);
            $user->setId($id);
            $format = 'Y-m-d H:i:s';
            $registration_date = DateTime::createFromFormat($format, $result['registration_date']);
            $user->setRegistrationDate($registration_date);
            $users[] = $user;
        }

        return $users;
    }

    public function findById(int $user_id) : ?User {
        $query = $this->db->prepare('SELECT * FROM users WHERE user_id = :user_id');
        $parameters = [
            'user_id' => $user_id
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            $user = new User($result['username'], $result['email'], $result['password'], $result['intro']);
            $format = 'Y-m-d H:i:s';
            $registration_date = DateTime::createFromFormat($format, $result['registration_date']);
            $user->setRegistrationDate($registration_date);
            $user->setId($result['user_id']);
            return $user;
        }
        else
        {
            return null;
        }
    }



    public function findByEmail(string $email) : ? User {
        $query = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $parameters = [
            'email' => $email
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            $user = new User($result['username'], $result['email'], $result['password'], $result["intro"], $result["user_id"]);
            $format = 'Y-m-d H:i:s';
            $registration_date = DateTime::createFromFormat($format, $result['registration_date']);
            $user->setRegistrationDate($registration_date);
            return $user;
        }
        else
        {
            return null;
        }
    }

    public function create(User $user) : bool {
        $query = $this->db->prepare("INSERT INTO users (username, email, password, intro, registration_date) VALUES (:username, :email, :password, :intro, :registration_date)");


        $parameters = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'intro' => $user->getIntro(),
            'registration_date' => $user->getRegistrationDate()->format('Y-m-d H:i:s')
        ];


        $ret = $query->execute($parameters);

        if($ret) {
            return true;
        } else {
            return false;
        }
    }

    //A FINIR
    public function update(User $user) : bool
    {
        $query = $this->db->prepare("UPDATE users 
                                    SET username = :username, email = :email, password = :password, intro = :intro
                                    WHERE user_id = :user_id");

        $parameters = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'intro' => $user->getIntro(),
            'user_id' => $user->getId()
        ];


        $ret = $query->execute($parameters);

        if($ret) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(int $id) : bool {

        $query = $this->db->prepare("DELETE FROM users WHERE id = :id");

        $parameters = [
            "id" => $id
        ];

        $query->execute($parameters);

        return true;
    }
}