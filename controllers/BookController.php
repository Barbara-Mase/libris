<?php

class BookController extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function detailBook(int $id) : void {

        $_SESSION['errors'] = [];
        $bm = new BookManager();
        $book = $bm->findById($id);

        if($book) {
            $this->render('book', [
                'book' => $book
            ]);
        } else {
            $_SESSION["errors"]["book"] = "Book not found";
            $errors = $_SESSION["errors"];
            $this->render('home', [
                'errors' => $errors
            ]);
        }

    }


    //à ajouter : une vérification des erreurs potentiels
    public function checkCreate() : void

    {

        //Vérifier si le livre existe en bdd
        $bm = new BookManager();
        $book = $bm->findByKey($_POST['key']);


        //s'il n'existe pas on le créer
        if(!$book) {
            $newBook = new Book($_POST['key'], $_POST["title"], $_POST["author"], $_POST["publish_year"], $_POST["cover_id"]);
            //on assigne le nouveau a une variable book, le livre est retourné par la fonction create
            $book = $bm->createBook($newBook);
        }

        //On récupère l'id du livre qu'on envoie à javascript via un json_encode()
        $book_id = $book->getId();
        echo json_encode($book_id);




    }

    //en chantier
    public function addToList() : void {

        $bm = new BookManager();
        $book = $bm->findById($_POST["book_id"]);

        if($_SESSION['user_id']){
            $user_id = $_SESSION['user_id'];
        }

        if($book) {
            $book_id = $book->getId();
        }
        $bm->addBookUser($book_id, $user_id);

    }


}