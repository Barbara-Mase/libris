<?php


class HomeController extends AbstractController

{

        public function home() : void
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $success = $_SESSION['success'] ?? [];
        unset($_SESSION['success']);

        $um = new UserManager();

        $isAdmin = false;
        $sessionUserId = '';
        if(!empty($_SESSION["user_id"])){
            $sessionUserId = $_SESSION["user_id"];
            $user = $um->findById($sessionUserId);
            if($user->getRole() === "ADMIN") {
                $isAdmin = true;
            }
        }

        $this->render('home', [
            'errors' => $errors,
            'success' => $success,
            'sessionUserId' => $sessionUserId,
            'isAdmin' => $isAdmin
        ]);
    }

    public function research() : void {

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $this->render('research', [
            'errors' => $errors
        ]);
    }

    /*public function profileLink(): void {

        $_SESSION["user_id"] = $_GET["id"];
        $_SESSION["errors"] = [];
        if(!empty($_SESSION["user_id"])) {
            $this->render("profile");
        } else {
            $_SESSION["errors"]["profile"] = "You must be logged in to view this page.";
            $this->render("home", [
                "errors" => $_SESSION["errors"]
            ]);
        }

    }*/

}