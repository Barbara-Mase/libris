<?php

class Comment {

    private ?int $commentId;

    public function __construct(private string $title, private string $content, private int $bookId) {
    }

    public function getCommentId(): ?int {
        return $this->commentId;
    }
    public function setCommentId(?int $commentId): void {
        $this->commentId = $commentId;
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

    public function getBookId(): ?int {
        return $this->bookId;
    }

    public function setBookId(?int $bookId): void {
        $this->bookId = $bookId;
    }
}