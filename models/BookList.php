<?php

class BookList
{
    private ?int $book_list_id;
  public function __construct(private string $title) {

  }

    /**
     * @return int|null
     */
    public function getBookListId(): ?int
    {
        return $this->book_list_id;
    }

    /**
     * @param int|null $book_list_id
     */
    public function setBookListId(?int $book_list_id): void
    {
        $this->book_list_id = $book_list_id;
    }

    public function getTitle(): string {
      return $this->title;
    }

    public function setTitle(string $title): void {
      $this->title = $title;
    }




}