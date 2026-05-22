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
        $user = $um->findById($id);

         if ($user) {
             $userId = $user->getId();
             //Il faut être connecté pour accéder au profil
             if (!empty($_SESSION['user'])) {
                     unset($_SESSION["errors"]);
                     $this->render('user', [
                         'user' => $user
                     ]);
             } else {
                 $_SESSION["errors"][] = "Access denied";
                 header("Location: index.php?route=home");
             }
         } else {
             $_SESSION["errors"][] = "User not found";
             header("location: index.php?home");
         }
    }

}