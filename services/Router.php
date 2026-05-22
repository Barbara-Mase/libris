<?php

class Router {

    private HomeController $hc;

    private BookListController $blc;
    private BookController $bc;
    private UserController $uc;
    private AuthController $ac;


    public function __construct() {
        $this->hc = new HomeController();
        $this->blc = new BookListController();
        $this->bc = new BookController();
        $this->uc = new UserController();
        $this->ac = new AuthController();
    }



    public function handleRequest(array $get) : void {
        if(isset($get['route'])){
            if($get['route'] === 'home'){;
                $this->hc->home();
            }
            else if($get['route'] === 'category'){
                echo "Category";
            }
            else if ($get['route'] === 'list') {
                $this->blc->detailList(1);
            }
            else if ($get['route'] === 'create-list') {
                echo "Create List";
            }
            else if ($get['route'] === 'add-book-list') {
                $this->blc->addBookList();
                echo 'passage du router';
            }
            else if ($get['route'] === 'delete-book-list') {
                echo "Delete Book List";
            }
            else if ($get['route'] === 'edit-book-list') {
                echo "Edit Book List";
            }
            //Gestion de l'affiche et des requête des livres par le bookController
            else if($get["route"] === 'detail-book') {
                $this->bc->detailBook(intval($get["id"]));
            }
            else if($get["route"] === 'create-book') {
            }
            else if ($get["route"] === 'check-create-book') {
                $this->bc->checkCreate();
            }
            else if ($get["route"] === 'delete-book') {
                echo "Delete Book";
            }
            //Gestion des utilisateurs
            else if($get['route'] === "profile") {
                if(!empty($get["id"])) {
                    $this->uc->profile(intval($get['id']));
                }
            }
            else if ($get['route'] === "users-list") {
                $this->uc->list();
            }
            else if ($get['route'] === "create-user") {
                $this->ac->createUser();
            }
            else if ($get['route'] === "check-create-user") {
                $this->ac->checkCreateUser();
            }
            else if ($get['route'] === "update-user") {
                if(!empty($get["id"])) {
                    $this->ac->updateUser(intval($get['id']));
                }
            } else if ($get['route'] === "check-update-user") {
                if(!empty($get["id"])) {
                    $this->ac->checkUpdateUser(intval($get['id']));
                }
            } else if ($get['route'] === "delete-user") {
                if(!empty($get["id"])) {
                    $this->ac->deleteUser(intval($get['id']));
                }
            } else if ($get['route'] === "login") {
                    $this->ac->login();
            } else if ($get['route'] === "check-login") {
                $this->ac->checkLogin();
            } else if ($get['route'] === "logout") {
                $this->ac->logout();
            }
            //si le chemin n'existe pas, affichage d'une erreur
            else {
                echo "Cette page n existe pas";
            }
        // Si aucun chemin n'est spécifié, on redirige vers "home"
        } else {
            $this->hc->home();
        }

    }

}
