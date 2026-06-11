<?php


class HomeController extends AbstractController

{

    public function home(): void
    {

        $errors = null;
        if(!empty($_SESSION["errors"])){
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('home', [
                'errors' => $errors
                ]);
        } else {
            $this->render('home');
        }



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