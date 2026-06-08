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
            $this->render('detail-book', [
                'book' => $book
            ]);
            $_SESSION["book_id"] = $id;
        } else {
            $_SESSION["errors"]["book"] = "Book not found";
            $errors = $_SESSION["errors"];
            $this->render('detail-book', [
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
        $book_id = $book->getBookId();
        echo json_encode($book_id);

    }

    //en chantier
    public function addToList() : void {

        $_SESSION['errors'] = [];
        $bm = new BookManager();

        if ($_SESSION['user_id']) {
            $userId = $_SESSION['user_id'];
            if($_SESSION['book_id']) {
                $bookId = $_SESSION['book_id'];
                $bm->addBookUser($bookId, $userId);
            } else {
                $_SESSION["errors"]["book"] = "Book not found";
                //décider où rediriger
                $this->redirect("route=home");
            }
        } else {
            $_SESSION["errors"]["book"] = "You must be logged in to add a book";
            //décider où rediriger
            $this->redirect("route=home");
        }

    }

    /*public function booksList(int $userId) : void
    {

        $bm = new BookManager();
        $bookIdList = $bm->findBookUsers($userId);
        $_SESSION['errors'] = [];

        if ($bookIdList) {
            foreach ($bookIdList as $arrayBookId)
            {
                foreach ($arrayBookId as $bookId) {
                    $book = $bm->findById($bookId);
                    $booksList[] = $book;
                }
            }
            $this->render('user', [
                'booksList' => $booksList
            ]);
        } else {
            $_SESSION["errors"]["list"] = "No book yet in list";
        }
    }*/

}