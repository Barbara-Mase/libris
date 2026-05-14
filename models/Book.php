<?php

class Book {
    private ?int $book_id = NULL;


    public function __construct(private string $book_key, private string $title, private string $author, private int $publish_year, private ?string $cover_id) {

    }

    /**
     * @return int|null
     */
    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    /**
     * @param int|null $book_id
     */
    public function setBookId(?int $book_id): void
    {
        $this->book_id = $book_id;
    }

    /**
     * @return string
     */
    public function getBookKey(): string
    {
        return $this->book_key;
    }

    public function setBookKey(string $book_key): void
    {
        $this->book_key = $book_key;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return int
     */
    public function getPublishYear(): int
    {
        return $this->publish_year;
    }

    /**
     * @param int $publication_date
     */
    public function setPublishYear(int $publication_year): void
    {
        $this->publish_date = $publish_year;
    }
    public function getCoverId(): ?string
    {
        return $this->cover_id;
    }

    public function setCoverId(?string $cover_id): void
    {
        $this->cover_id = $cover_id;
    }


}