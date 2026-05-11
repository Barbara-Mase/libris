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


    public function findById(int $id) : ?Book {

        $query = $this->db->prepare('SELECT * FROM books WHERE id = :id');

        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);

        $result = $query->fetch(PDO::FETCH_ASSOC);

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

    public function findByKey(string $key) : ?Book {

        $query = $this->db->prepare('SELECT * FROM books WHERE book_key = :book_key');

        $parameters = [
            'book_key' => $key
        ];

        $result = $query->execute($parameters);

        if($result)
        {
            $book = new Book($result['key'], $result['title'], $result['author'], $result['publication_year'], $result['cover_id']);
            return $book;
        }
        else
        {
            return null;
        }
    }


}