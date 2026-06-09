<?php

class CommentController extends AbstractController
{
    public function __construct() {
        parent::__construct();
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
}