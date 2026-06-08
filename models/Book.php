<?php

class Book {
    private ?int $bookId = NULL;


    public function __construct(private string $bookKey, private string $title, private string $author, private int $publishYear, private ?string $coverId) {

    }

    /**
     * @return int|null
     */
    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    /**
     * @param int|null $book_id
     */
    public function setBookId(?int $bookId): void
    {
        $this->bookId = $bookId;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->bookKey;
    }

    public function setKey(string $bookKey): void
    {
        $this->bookKey = $bookKey;
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
        return $this->publishYear;
    }

    /**
     * @param int $publication_date
     */
    public function setPublishYear(int $publishYear) : void
    {
        $this->publishYear = $publishYear;
    }
    public function getCoverId(): ?string
    {
        return $this->coverId;
    }

    public function setCoverId(?string $coverId): void
    {
        $this->coverId = $coverId;
    }


}