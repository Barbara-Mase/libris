<?php

class Router {

    private AbstractController $controller;

    private HomeController $hc;

    private BookListController $blc;
    private BookController $bc;
    private UserController $uc;
    private AuthController $ac;

    private CommentController $cc;
    private AdminController $adminc;


    public function __construct() {
        $this->hc = new HomeController();
        $this->bc = new BookController();
        $this->uc = new UserController();
        $this->ac = new AuthController();
        $this->cc = new CommentController();
        $this->adminc = new AdminController();
    }



    public function handleRequest(array $get) : void
    {
        if(isset($get['route'])) {

            if($get['route'] === 'home') {
                $this->hc->home();
            }
            else if($get['route'] === 'research') {
                $this->hc->research();
            }
            else if ($get['route'] === 'add-to-list')
            {
                $this->bc->addToList();
            }
            else if ($get['route'] === 'remove-book-from-list')
            {
                if (!empty($get["id"])) {
                    $this->bc->removeBookFromList($get['id']);
                }
            }
            else if($get["route"] === 'detail-book')
            {
                if (!empty($get['id']))
                {
                    $this->bc->detailBook(intval($get["id"]));
                }
            }
            else if ($get["route"] === 'check-create-book')
            {
                $this->bc->checkCreate();
            }
            else if ($get["route"] === 'delete-book')
            {
                echo "Delete Book";
            }
            else if ($get["route"] === 'add-comment')
            {
                if (!empty($get["id"])) {
                    $this->cc->addComment(intval($get["id"]));
                }
            }
            else if ($get["route"] === 'delete-comment') {
                if (!empty($get["id"])) {
                    $this->cc->deleteComment(intval($get["id"]));
                }
            }
            else if ($get["route"] === 'update-comment') {
                if (!empty($get["id"])) {
                    $this->cc->updateComment(intval($get["id"]));
                }
            }
            //Gestion des utilisateurs
            else if($get['route'] === "profile")
            {
                if (!empty($get["id"])) {
                    $this->uc->profile(intval($get['id']));
                } else {
                    $_SESSION['errors']['not_logged_in'] = 'You must be logged in to access this page.';
                }
            }
            else if ($get['route'] === "users-list")
            {
                $this->uc->list();
            }
            else if ($get['route'] === "create-user")
            {
                $this->ac->createUser();
            }
            else if ($get['route'] === "check-create-user")
            {
                $this->ac->checkCreateUser();
            }
            else if ($get['route'] === "update-user")
            {
                if(!empty($get["id"])) {
                    $this->uc->updateUser(intval($get['id']));
                }
            }
            else if ($get['route'] === "check-update-user")
            {
                if(!empty($get["id"])) {
                    $this->uc->checkUpdateUser(intval($get['id']));
                }
            }
            else if ($get['route'] === "delete-user") {
                if(!empty($get["id"]))
                {
                    $this->ac->deleteUser(intval($get['id']));
                }
            }
            else if ($get['route'] === "login")
            {
                    $this->ac->login();
            }
            else if ($get['route'] === "check-login")
            {
                $this->ac->checkLogin();
            }
            else if ($get['route'] === "logout") {
                $this->ac->logout();
            }
            //routes admin ci-dessous
            else if ($get['route'] === "admin-home") {
                $this->adminc->adminHome();
            }
                //routes gestion des livres
            else if ($get['route'] === "admin-books-list") {
                $this->adminc->showAllBooks();
            }
            else if ($get['route'] === "admin-delete-book") {
                if (!empty($get["id"])) {
                    $this->adminc->deleteBook(intval($get["id"]));
                }
            }
            else if ($get['route'] === "admin-edit-book") {
                if (!empty($get["id"])) {
                    $this->adminc->editBook(intval($get["id"]));
                }
            }
            else if ($get['route'] === "admin-check-edit-book") {
                if (!empty($get["id"])) {
                    $this->adminc->checkEditBook(intval($get["id"]));
                }
            }
                //routes gestion des commentaires
            else if($get["route"] === "admin-comments-list") {
                $this->adminc->showAllComments();
            }
            else if ($get["route"] === "admin-delete-comment") {
                if (!empty($get["id"])) {
                    $this->adminc->deleteComment(intval($get["id"]));
                }
            }
                //route festion des utilisateurs
            else if ($get["route"] === "admin-users-list") {
                $this->adminc->showAllUsers();
            }
            else if ($get["route"] === "admin-delete-user") {
                if (!empty($get["id"])) {
                    $this->adminc->deleteUser(intval($get["id"]));
                }
            }
            //si le chemin n'existe pas, affichage d'une erreur
            else {
                $_SESSION['errors']['invalidRoute'] = "This route does not exist";
                $this->hc->home();
            }
        // Si aucun chemin n'est spécifié, on redirige vers "home"
        } else {
            $this->hc->home();
        }

    }

}
