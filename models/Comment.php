<?php

class Comment {

    private ?int $commentId;
    private DateTime $publishDate;
    private User $author;

    public function __construct(private ?int $bookId, private string $title, private string $content) {
    }

    public function getCommentId(): ?int {
        return $this->commentId;
    }
    public function setCommentId(?int $commentId): void {
        $this->commentId = $commentId;
    }
    public function getAuthor(): User {
        return $this->author;
    }

    public function setAuthor(User $author): void {
        $this->author = $author;
    }

    public function getBookId(): ?int {
        return $this->bookId;
    }
    public function setBookId(?int $bookId): void {
        $this->bookId = $bookId;
    }
    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }
    public function getContent(): string {
        return $this->content;
    }
    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getPublishDate(): DateTime {
        return $this->publishDate;
    }

    public function setPublishDate(DateTime $publishDate): void {
        $this->publishDate = $publishDate;
    }

}