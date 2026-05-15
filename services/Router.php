<?php

class Router {


    public function __construct(){

    }

    public function handleRequest(array $get) : void {
        if(isset($get['route'])){
            if($get['route'] === 'home'){
                $hm = new HomeController();
                $hm->home();
            }
            else if($get['route'] === 'category'){
                echo "Category";
            }
            else if ($get['route'] === 'list') {
                $blc = new BookListController();
                $blc->detailList(1);
            }
            else if ($get['route'] === 'create-list') {
                echo "Create List";
            }
            else if ($get['route'] === 'add-book-list') {
                $blc = new BookListController();
                $blc->addBookList();
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
                $bc = new BookController();
                $bc->detailBook(intval($get["id"]));
            }
            else if($get["route"] === 'create-book') {
            }
            else if ($get["route"] === 'check-create-book') {
                $bc = new BookController();
                $bc->checkCreate();
            }
            else if ($get["route"] === 'delete-book') {
                echo "Delete Book";
            }
            //Gestion des utilisateurs
            else if ($get["route"] === 'registration') {
                $ac = new AuthController();
                $ac->showRegistrationForm();
            }
            else if($get['route'] === "profile") {
                $uc = new UserController();
                $uc->details(intval($get['id']));
            } else if ($get['route'] === "create-user") {
                $uc = new AuthController();
                $uc->createUser();
            } else if ($get['route'] === "check-create-user") {
                $uc = new AuthController();
                $uc->checkCreateUser();
            }
            //si le chemin n'existe pas, affichage d'une erreur
            else {
                echo "Cette page n existe pas";
            }
        // Si aucun chemin n'est spécifié, on redirige vers "home"
        } else {
            $hm = new HomeController();
            $hm->home();
        }

    }

}
