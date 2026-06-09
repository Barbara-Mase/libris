<?php

class Comment {

    private ?int $commentId;
    private DateTime $publishDate;

    public function __construct(private string $title, private string $content) {
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

    public function getPublishDate(): DateTime {
        return $this->publishDate;
    }

    public function setPublishDate(DateTime $publishDate): void {
        $this->publishDate = $publishDate;
    }

}