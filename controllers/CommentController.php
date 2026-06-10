<?php

class CommentController extends AbstractController
{
    public function __construct() {
        parent::__construct();
    }

    public function showCommentsByBook(int $bookId) : void {

        $cm = new CommentManager();

        $comments = $cm->findCommentsByBookId($bookId);

        if(isset($comments)) {
            $this->render('detail-book', [
                "comments" => $comments
            ]);
        }
    }

    public function showCommentByUser(int $userId) : void {

        $cm = new CommentManager();

        $comments = $cm->findCommentsByUserId($userId);

        if(isset($comments)) {
            $this->render('user', [
                "comments" => $comments
            ]);
        }
    }
    public function addComment(int $bookId) : void {

        $cm = new CommentManager();

        if (isset($_SESSION['user_id'])) {
            if (!empty($_POST['comment-title']) && !empty($_POST['comment-content'])) {
                $title = htmlspecialchars($_POST["comment-title"]);
                $content = htmlspecialchars($_POST["comment-content"]);
                $comment = new Comment($title, $content,);
                $date = new DateTime();
                $comment->setPublishDate($date);
                $userId = $_SESSION['user_id'];
                $cm->comment($bookId, $userId, $comment);
            } else {
                $_SESSION['error']['comment'] = 'Missing fields';
            }
        } else {
            $_SESSION['error']['comment'] = 'You must be logged in to post comments.';
        }
    }

    public function updateComment(int $commentId) : void {

        $_SESSION['error']= '';
        $cm = new CommentManager();

        //$comment est un tableau associatif qui contient 'user_id' issu de la table book_com_user et 'com_id' issu de la table comments
        $comment = $cm->findCommentById($commentId);
        $userId = $_SESSION['user_id'];

        if (isset($userId)) {
            //si l'identifiant d'utilisateur en session et le même que l'identifiant correspond au commentaire, alors l'utilisateur a la possibilité de le modifier
            if($userId === $comment['user_id']) {
                if (!empty($_POST['comment-title']) && !empty($_POST['comment-content'])) {
                    $commentTitle = htmlspecialchars($_POST["comment-title"]);
                    $commentContent = htmlspecialchars($_POST["comment-content"]);
                    $updatedComment = new Comment($commentTitle, $commentContent);
                    $cm->update($updatedComment);
                } else {
                    $_SESSION['error']['comment'] = 'Missing fields';
                }
            } else {
                $_SESSION['error']['comments'] = 'Access denied';
            }
        } else {
            $_SESSION['error']['comment'] = 'You must be logged in to update comments.';
        }
    }

    public function deleteComment(int $commentId) : void {

        $cm = new CommentManager();
        $comment = $cm->findCommentById($commentId);

        $userId = $_SESSION['user_id'];

        if (isset($userId)) {
            if($userId === $comment['user_id']) {
                $cm->delete($commentId);
            }
        }
    }
}