<?php

class AdminController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }




    public function deleteBook(int $bookId): void {
        $bm = new BookManager();
        $um = new UserManager();

        $user_id = $_SESSION['user_id'];
        $user = $um->findById($user_id);

        $tokenManager = new CSRFTokenManager();

        if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

            if ($user->getRole() === 'ADMIN') {
                $bm->deleteBook($bookId);
            }
        } else {
            $_SESSION['errors']['csrf_token'] = "CSRF Token Error";
            // Rediriger
        }
    }

    public function deleteComment(int $commentId): void {

    }

    public function deleteUser(int $userId): void {

    }
}