<?php


class HomeController extends AbstractController

{

    public function home(): void
    {

        if(!empty($_SESSION["errors"])){
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render("home", [
                "errors" => $errors
            ]);
        } else {
            $this->render("home");
        }

    }

}