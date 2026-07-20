<?php

class CommentController extends AbstractController
{
    public function __construct() {
        parent::__construct();
    }


    public function addComment(int $bookId) : void {

        $cm = new CommentManager();

        if (!empty($_SESSION['user_id'])) {

            $tokenManager = new CSRFTokenManager();

            if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

                if (!empty($_POST['comment-title']) && !empty($_POST['comment-content'])) {
                    $title = htmlspecialchars($_POST["comment-title"]);
                    $content = htmlspecialchars($_POST["comment-content"]);
                    $userId = $_SESSION['user_id'];
                    $comment = new Comment($title, $content);
                    $comment->setUserId($userId);
                    $comment->setBookId($bookId);
                    $date = new DateTime();
                    $comment->setPublishDate($date);
                    $cm->comment($comment);
                    $this->redirect('route=detail-book&id=' . $bookId);
                } else {
                    $_SESSION['errors']['comment'] = 'Missing fields';
                    $this->redirect('route=detail-book&id=' . $bookId);
                }
            } else {
                $_SESSION['errors']['comment'] = 'Invalid CSRF token.';
                $this->redirect('route=detail-book&id=' . $bookId);
            }
        } else {
            $_SESSION['errors']['comment'] = 'You must be logged in to post comments.';
            $this->redirect('route=detail-book&id=' . $bookId);
        }
    }


    // FONCTION UPDATE A IMPLEMENTER SI TEMPS DISPONIBLE //////


   /* public function updateComment(int $commentId) : void {

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
    }*/

    public function deleteComment(int $commentId) : void {

        $cm = new CommentManager();
        $comment = $cm->findCommentById($commentId);

        $userId = $_SESSION['user_id'];
        if (isset($userId)) {
            //On vérifie ici que l'identifiant d'utilisateur en session est le même que celui de l'auteur du commentaire
            //Seul l'utilisateur auteur (et les administrateurs) peuvent supprimer un commentaire
            if($userId === $comment->getUserId()) {
                $cm->delete($commentId);
                $this->redirect("route=profile&id=" . $userId);
            }
        } else {
            $_SESSION['errors']['comment'] = 'You must be logged in to delete comments.';
            $this->redirect('route=profile&id=' . $userId);
        }
    }
}