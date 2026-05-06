<?php

class BookList
{
    private ?int $id;
  public function __construct(private string $title, private int $user_id, private array $book_ids) {

  }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
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

    public function getUserId(): int
    {
      return $this->user_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getBookIds(): array {
      return $this->book_ids;
    }

    public function setBookIds(array $book_ids): void {
      $this->book_ids = $book_ids;
    }




}