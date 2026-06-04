<?php


class BookManager extends AbstractManager {

    public function __construct() {
        parent::__construct();
    }

    public function createBook(Book $book) : ?Book {


        $query = $this->db->prepare("INSERT INTO books (book_key, title, author, publish_year, cover_id) VALUES (:book_key, :title, :author, :publish_year, :cover_id)");

        $parameters = [
            'book_key' => $book->getKey(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'publish_year' => $book->getPublishYear(),
            'cover_id' => $book->getCoverId()
        ];

        $ret = $query->execute($parameters);

        if($ret) {
            $book = new Book($_POST['key'], $_POST['title'], $_POST['author'], $_POST['publish_year'], $_POST['cover_id']);
            $book->setBookId($this->db->lastInsertId());
            return $book;
        } else {
            return null;
        }

    }


    public function findById(int $book_id) : ?Book {

        $query = $this->db->prepare('SELECT * FROM books WHERE book_id = :book_id');

        $parameters = [
            'book_id' => $book_id
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            $book = new Book($result['book_key'], $result['title'], $result['author'], $result['publish_year'], $result['cover_id']);
            return $book;
        }
        else
        {
            return null;
        }
    }

    public function findByKey(string $key): ?Book
    {

        $query = $this->db->prepare('SELECT * FROM books WHERE book_key = :book_key');

        $parameters = [
            'book_key' => $key
        ];

        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $book = new Book($result['book_key'], $result['title'], $result['author'], $result['publish_year'], $result['cover_id']);
            $book->setBookId($result['book_id']);
            return $book;

        } else {
            return null;
        }

        /*$result = $query->fetchColumn();

        return (bool) $result;*/

    }
    /*public function findByKey(string $book_key) : bool
    {

        $query = $this->db->prepare('SELECT * FROM books WHERE book_key = :book_key');

        $parameters = [
            'book_key' => $book_key
        ];

        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }*/

    public function addBookUser(int $book_id, int $user_id) : bool {

        $query = $this->db->prepare("INSERT INTO books_users (book_id, user_id) VALUES (:book_id, :user_id)");

        $parameters = [
            'book_id' => $book_id,
            'user_id' => $user_id
        ];
        $result = $query->execute($parameters);


        if($result) {
            return true;
        } else {
            return false;
        }
    }

    //a completer
    //public function removeBookUser(int $book_id, int $user_id) : bool {}

    //à compléter
    //public function removeBook(int $book_id) : bool {}

    public function findBookUsers(int $user_id) : ?array {

        $query = $this->db->prepare('SELECT book_id FROM books_users WHERE user_id = :user_id');

        $parameters = [
            'user_id' => $user_id
        ];

        $query->execute($parameters);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            return null;
        }

        $list = [];

        foreach($result as $row) {
            $list[] = $row['book_id'];
            return $list;
        }

        return $list;
    }

}