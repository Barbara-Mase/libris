<?php

use MongoDB\Driver\Manager;

class   CommentManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findCommentsByBookId(int $bookId)
    {

        $query = $this->db->prepare('SELECT comments.title,comments.content, comments.publish_date FROM comments JOIN book_com_user ON comments.com_id = book_com_user.com_id WHERE book_com_user.book_id = :book_id');

        $parameters = [
            "book_id" => $bookId
        ];

        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);


        if (!$results) {
            return null;
        } else {
            $comments = [];
            foreach ($results as $result) {
                $comment = new Comment($result['title'], $result['content'], $result['publish_date']);
                $id = intval($result['com_id']);
                $comment->setCommentId($id);
                $format = 'Y-m-d H:i:s';
                $publishDate = DateTime::createFromFormat($format, $result['publish_date']);
                $comment->setPublishDate($publishDate);
                $comments[] = $comment;
            }

            return $comments;
        }
    }

    public function findCommentsByUserId(int $userId)
    {

        $query = $this->db->prepare('SELECT comments.title,comments.content, comments.publish_date FROM comments JOIN book_com_user ON comments.com_id = book_com_user.com_id WHERE book_com_user.user_id = :user_id');

        $parameters = [
            "user_id" => $userId
        ];

        $query->execute($parameters);

        $results = $query->fetchAll(PDO::FETCH_ASSOC);


        if (!$results) {
            return null;
        } else {
            $comments = [];
            foreach ($results as $result) {
                $comment = new Comment($result['title'], $result['content'], $result['publish_date']);
                $id = intval($result['com_id']);
                $comment->setCommentId($id);
                $format = 'Y-m-d H:i:s';
                $publishDate = DateTime::createFromFormat($format, $result['publish_date']);
                $comment->setPublishDate($publishDate);
                $comments[] = $comment;
            }

            return $comments;
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

}