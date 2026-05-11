<?php

class BookListManager extends AbstractManager {

    public function __construct() {
        parent::__construct();
    }


    public function findOne(int $id) : ?BookList {
        $query =  $this->db->prepare("SELECT * FROM `lists` WHERE `id` = :id");

        $parameters = [
            'id' => $id
        ];

        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $list = new BookList($result['title'], $result["user_id"], $result["book_ids"]);
            return $list;
        } else {
            return null;
        }

    }

    public function findAllListUser(int $user_id) : array
    {
        $query =  $this->db->prepare("SELECT * FROM `lists` WHERE `user_id` = :user_id");

        $parameters = [
            'user_id' => $user_id
        ];

        $query->execute($parameters);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $lists = [];
        foreach ($result as $row) {
            $list = new BookList($result['title'], $result["user_id"], $result["book-ids"]);
            $lists[] = $list;
            }
            return $lists;
    }

    public function createList(int $id) : bool {

        $query = $this->db->prepare('INSERT INTO `list`');

        $query->execute();

        if($this->db->lastInsertId()) {
            return true;
        } else {
            return false;
        }
    }
}