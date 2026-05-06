<?php


class BookManager extends AbstractManager {

    public function __construct() {
        parent::__construct();
    }

    public function createBook(book $book) : bool {

        $query = $this->db->prepare("INSERT INTO books (book_key, title, author, publish_year, cover_id) VALUES (:book_key, :title, :author, :publish_year, :cover_id)");

        $parameters = [
            'book_key' => $book->getBookKey(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'publish_year' => $book->getPublishYear(),
            'cover_id' => $book->getCoverId()
        ];

        $query->execute($parameters);

        if($this->db->lastInsertId()) {
            return true;
        } else {
            return false;
        }

    }

    public function findOne(int $id) : ?Book {

        $query = $this->db->prepare('SELECT * FROM books WHERE id = :id');

        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);
        var_dump($result);
        if($result)
        {
            $book = new Book($result['title'], $result['author'], $result['publication_year'], $result['cover_id']);
            return $book;
        }
        else
        {
            return null;
        }
    }


}