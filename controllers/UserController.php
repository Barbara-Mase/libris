<?php


class UserController extends AbstractController {

    public function __construct(){
        parent::__construct();
    }


    public function list() : void
    {
        $um = new UserManager();
        $users = $um->findAll();

        // attention à twig
        $this->render('users-list', [
            'users' => $users
        ]);
    }

    public function profile(int $id) : void
    {

        $_SESSION["errors"] = [];
        $um = new UserManager();

        //on cherche les infos du profil
        if ($id) {
            $user = $um->findById($id);
            if ($user) {
                unset($_SESSION["errors"]);
            } else {
                $_SESSION["errors"]["access_denied"] = "User not found";
                $errors = $_SESSION["errors"];
                $this->render('user', [
                    'errors' => $errors,
                ]);
                return;
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $errors = $_SESSION["errors"];
            $this->render('user', [
                'errors' => $errors,
            ]);
            return;
        }

        $bm = new BookManager();
        //bookIdList est un tableau de tableau donc on boucle deux fois
        $bookIdList = $bm->findBookUsers($id);
        $booksList = [];

        if ($bookIdList) {
            foreach ($bookIdList as $arrayBookId) {
                foreach ($arrayBookId as $bookId) {
                    $book = $bm->findById($bookId);
                    $booksList[] = $book;
                }
            }
        }

        $cm = new CommentManager();
        $comments = $cm->findCommentByUserId($id);
        //s'il n'y a pas d'erreur, on envoie tout à la vue
        $this->render('user', [
            'user' => $user,
            'booksList' => $booksList,
            'comments' => $comments
        ]);
    }

}