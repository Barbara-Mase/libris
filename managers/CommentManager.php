<?php

use MongoDB\Driver\Manager;
use PharIo\Manifest\Author;

class   CommentManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findCommentsByBookId(int $bookId): array
    {
        $query = $this->db->prepare("SELECT * FROM comments WHERE comments.book_id = :bookId");

        $parameters = [
            "bookId" => $bookId
        ];
        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        $um = new UserManager();

        foreach ($results as $result) {
            $comment = new Comment($result['user_id'], $result['book_id'], $result['title'],$result['content']);
            $format = 'Y-m-d H:i:s';
            $publishDate = DateTime::createFromFormat($format, $result['publish_date']);
            $comment->setPublishDate($publishDate);
            $author = $um->findById($result['user_id']);
            $comment->setAuthor($author);

            $comments[] = $comment;
        }

        return $comments;

    }

    public function findCommentByUserId($userId) : array
    {
        $query = $this->db->prepare("SELECT * FROM comments WHERE user_id = :userId");

        $parameters = [
            'userId' => $userId
        ];
        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        $bm = new BookManager();

        foreach ($results as $result) {
            $comment = new Comment($result['user_id'], $result['book_id'], $result['title'],$result['content']);
            $format = 'Y-m-d H:i:s';
            $publishDate = DateTime::createFromFormat($format, $result['publish_date']);
            $comment->setPublishDate($publishDate);
            $book = $bm->findById($result['book_id']);
            $comment->setBook($book);

            $comments[] = $comment;
        }

        return $comments;

    }

    public function comment(Comment $comment): bool
    {

        $query = $this->db->prepare("INSERT INTO comments (book_id, user_id, title, content, publish_date) VALUES (:book_id, :user_id, :title, :content, :publish_date)");

        $parameters = [
            'book_id' => $comment->getBookId(),
            'user_id' => $comment->getUserId(),
            'title' => $comment->getTitle(),
            'content' => $comment->getContent(),
            'publish_date' => $comment->getPublishDate()->format('Y-m-d H:i:s')
        ];

        $result = $query->execute($parameters);

        if($result) {
            return true;
        } else {
            return false;
        }

    }

    public function update(Comment $comment) : bool
    {
        $query = $this->db->prepare("UPDATE comments SET title = :title, content = :content WHERE id = :id");

        $parameters = [
            'title' => $comment->getTitle(),
            'content' => $comment->getContent(),
        ];

        $result = $query->execute($parameters);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(int $commentId) : int {

        $query = $this->db->prepare("DELETE FROM comments WHERE id = :id");

        $query->execute([]);

        return $query->rowCount();

    }

}