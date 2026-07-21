<?php


class HomeController extends AbstractController

{

        public function home() : void
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $success = $_SESSION['success'] ?? [];
        unset($_SESSION['success']);

        $this->render('home', [
            'errors' => $errors,
            'success' => $success,
        ]);
    }

    public function research() : void {

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $this->render('research', [
            'errors' => $errors
        ]);
    }


}