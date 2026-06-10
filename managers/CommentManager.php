<?php

use MongoDB\Driver\Manager;
use PharIo\Manifest\Author;

class   CommentManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    /*SELECT comments.*, users.username as author FROM comments
    JOIN users on comments.user_id = users.id
    WHERE comments.book_id = :bookId*/
    public function findCommentsByBookId(int $bookId): array
    {
        $query = $this->db->prepare("SELECT comments.* FROM comments WHERE comments.book_id = :bookId");

        $parameters = [
            "bookId" => $bookId
        ];
        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        $um = new UserManager();

        foreach ($results as $result) {
            $comment = new Comment( $result['book_id'], $result['title'],$result['content']);
            $comment->setPublishDate($result['publish_date']);
            $author = $um->findById($result['user_id']);
            $comment->setAuthor($author);

            $comments[] = $comment;
        }

        return $comments;

    }

    public function findCommentByUserId($userId) : ?Comment
    {
        $query = $this->db->prepare("SELECT comments.*, user.username as author, books.title as title FROM comments JOIN users on comments.user_id = users.id JOIN books on comments.book_id = comments.book_id WHERE comments.book_id = :bookId");

        $parameters = [
            "user_id" => $userId
        ];

        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);

        if($results){
            foreach ($results as $result){
                $comment = new Comment( $result['user_id'], $result['book_id'], $result['com_title'], $result['content']);
                $comment->setPublishDate($result['publish_date']);

            }
            return $comment;
        } else {
            return null;
        }

    }

    public function comment(int $bookId, int $userId, Comment $comment): bool
    {
        try {
            //La transaction permet de faire en sorte que les deux opérations fonctionnent sans que l'une ou l'autre échoue
            //permet d'effectuer deux opérations comme si c'était une seule entité
            $this->db->beginTransaction();

            $query = $this->db->prepare("INSERT INTO comments (title, content, publish_date) VALUES (:title, :content, :publish_date)");

            $parameters = [
                'title' => $comment->getTitle(),
                'content' => $comment->getContent(),
                'publish_date' => $comment->getPublishDate()->format('Y-m-d H:i:s')
            ];

            $query->execute($parameters);

            //On récupère l'id du commentaires nouvellement créé
            $comId = $this->db->lastInsertId();
            //On l'ajoute à la table de liaison en même temps de l'user_id et le book_id
            $query = $this->db->prepare("INSERT INTO book_com_user (com_id, user_id, book_id) VALUES (:com_id, :user_id, :book_id)");

            $parameters = [
                'com_id' => $comId,
                'user_id' => $userId,
                'book_id' => $bookId,
            ];

            $query->execute($parameters);

            //On met fin à la transaction pour éviter les problèmes
            $this->db->commit();

            return true;

        } catch (PDOException $e) {
            //Si quelque chose se passe mal, on revient en arrière
            $this->db->rollBack();
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