<?php

class AdminController extends AbstractController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function adminHome() : void {

        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $um = new UserManager();
            $user = $um->findById($user_id);
            if ($user->getRole() !== 'ADMIN') {
                $_SESSION['errors']['access_denied'] = 'You are not allowed to access this page';
                $this->redirect('route=home');
            }
        } else {
            $_SESSION['errors']['access_denied'] = 'You are not allowed to access this page';
            $this->redirect('route=home');
        }


        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);


        $this->adminRender('admin/admin-home', [
            'errors' => $errors,
        ]);

    }
///GESTION DES LIVRES///
    public function showAllBooks(): void {

        $bm = new BookManager();
        $books = $bm->findAll();

        if(empty($books)){
            $_SESSION['errors']['books'] = 'Something went wrong';
            $this->redirect('route=admin-home');
        }

        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $um = new UserManager();
            $user = $um->findById($user_id);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }


        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        if($user->getRole() === 'ADMIN') {
            $this->adminRender("admin/admin-books-list",
                [
                    "books" => $books,
                    "errors" => $errors
                ]);
        } else {
            $_SESSION['errors']['users_list'] = 'You do not have permission to access this page';
            $this->redirect("route=home");
        }


    }
    public function deleteBook(int $bookId): void {

        $bm = new BookManager();

        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $um = new UserManager();
            $user = $um->findById($user_id);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }

        $tokenManager = new CSRFTokenManager();

        if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

            if ($user->getRole() === 'ADMIN') {
                $bm->deleteBook($bookId);
                $this->redirect('route=admin-books-list');
            } else {
                $_SESSION['errors']['access_denied'] = 'You are not allowed to do this action';
            }
        } else {
            $_SESSION['errors']['csrf_token'] = "CSRF Token Error";
            $this->redirect('route=home');
        }
    }

    public function editBook(int $bookId): void {

        $bm = new BookManager();
        $um = new UserManager();
        $book = $bm->findById($bookId);

        if (empty($book)) {
            $_SESSION['errors']['books'] = 'Book not found';
            $this->redirect('route=admin-books-list');
        }

        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user = $um->findById($user_id);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        if ($user->getRole() === 'ADMIN') {
            $this->adminRender("admin/admin-edit-book", [
                "errors" => $errors,
                "book" => $book
            ]);
        } else {
            $_SESSION['errors']['access_denied'] = 'You are not allowed to do this action';
            $this->redirect('route=home');
        }

    }

    public function checkEditBook(int $bookId): void
    {
        $bm = new BookManager();
        $um = new UserManager();
        $book = $bm->findById($bookId);

        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $user = $um->findById($user_id);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $tokenManager = new CSRFTokenManager();

        if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {
            if ($user->getRole() === 'ADMIN') {

                if (!empty($_POST['book-title']) && !empty($_POST['author']) && !empty($_POST['publish-year'])) {
                    $book->setTitle($_POST['book-title']);
                    $book->setAuthor($_POST['author']);
                    $book->setPublishYear($_POST['publish-year']);
                    $book->setCoverId($_POST['edit-cover-id']);
                    $book->setBookId($bookId);
                    $bm->update($book);
                    $this->redirect('route=admin-books-list');
                } else {
                    $_SESSION['errors']['missing_fields'] = 'Missing fields';
                    $this->redirect('route=admin-books-list');
                }
            } else {
                $_SESSION['errors']['access_denied'] = "You are not allowed to do this action";
                $this->redirect('route=home');
            }
        } else {
            $_SESSION['errors']['csrf_token'] = "CSRF Token Error";
            $this->redirect('route=admin-home');
        }
    }

//GESTION DES COMMENTAIRES//
    public function ShowAllComments(): void
    {

        $cm = new CommentManager();
        $comments = $cm->findAllComments();

        if (empty($comments)) {
            $_SESSION['errors']['comments'] = 'Something went wrong';
            $this->redirect('route=admin-home');
        }

        if (!empty($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $um = new UserManager();
            $user = $um->findById($user_id);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }


        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);


        if($user->getRole() === 'ADMIN') {
            $this->adminRender("admin/admin-comments-list",
                [
                    "comments" => $comments,
                    "errors" => $errors
                ]);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect("route=home");
        }

    }
    public function deleteComment(int $commentId): void {

        $cm = new CommentManager();
        $um = new UserManager();

        if (!empty($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION['user_id'];
            $sessionUser = $um->findById($sessionUserId);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }

        $tokenManager = new CSRFTokenManager();

        if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {
            if ($sessionUser->getRole() === 'ADMIN') {
                $cm->delete($commentId);
                $this->redirect('route=admin-comments-list');
            } else {
                $_SESSION['errors']['access_denied'] = 'You are not allowed to do this action';
            }
        } else {
            $_SESSION['errors']['csrf_token'] = "CSRF Token Error";
            $this->redirect('route=home');
        }
    }


    //GESTION DES UTILISATEURS//
    public function showAllUsers() : void
    {
        $um = new UserManager();
        $users = $um->findAll();

        if (empty($users)) {
            $_SESSION['errors']['comments'] = 'Something went wrong';
            $this->redirect('route=admin-home');
        }

        if (!empty($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION['user_id'];
            $sessionUser = $um->findById($sessionUserId);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }


        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);


        if ($sessionUser->getRole() === 'ADMIN') {
            $this->adminRender("admin/admin-users-list",
                [
                    "users" => $users,
                    "errors" => $errors
                ]);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect("route=home");
        }

    }



    public function deleteUser(int $userId): void {

        $um = new UserManager();

        if (!empty($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION['user_id'];
            $sessionUser = $um->findById($sessionUserId);
        } else {
            $_SESSION['errors']['access_denied'] = 'You do not have permission to access this page';
            $this->redirect('route=home');
        }

        $tokenManager = new CSRFTokenManager();

        if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

            if ($sessionUser->getRole() === 'ADMIN') {
                $um->delete($userId);
                $this->redirect('route=admin-users-list');
            } else {
                $_SESSION['errors']['access_denied'] = 'You are not allowed to do this action';
                $this->redirect('route=home');
            }
        } else {
            $_SESSION['errors']['csrf_token'] = "CSRF Token Error";
            $this->redirect('route=home');
        }
    }
}