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

        if ($id) {
            $user = $um->findById($id);

            if ($user) {
                unset($_SESSION["errors"]);
                $this->render('user', [
                    'user' => $user
                ]);
            } else {
                $_SESSION["errors"]["access_denied"] = "User not found";
                $errors = $_SESSION["errors"];
                $this->render('home', [
                    'errors' => $errors
                ]);
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $errors = $_SESSION["errors"];
                $this->render('home', [
                'errors' => $errors
            ]);
        }

    }

}