<?php

class Book{
    private ?int $id = NULL;

    public function __construct(private string $title, private string $author, private string $cover_id) {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function getCoverId(): string
    {
        return $this->cover_id;
    }

    public function setCoverId(string $cover_id): void
    {
        $this->cover_id = $cover_id;
    }


}