<?php

class BookController extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function detailBook(int $id) : void {

        $bm = new BookManager();
        $book = $bm->findById($id);

        $this->render('book', [
            'book' => $book
        ]);
    }

    public function checkCreate() : void

    {
        $_SESSION["errors"] = [];

        $bm = new BookManager();
        $book = $bm->findByKey($_POST['key']);

        if($book === false) {
            $newBook = new Book($_POST['key'], $_POST["title"], $_POST["author"], $_POST["publish_year"], $_POST["cover_id"]);
            $bm->createBook($newBook);
            //Remplacer par l'id du livre cliqué (test ici)
            //Ne fonctionne pas
            $this->redirect("index.php?route=detail-book&id=8");
        } else {
            //Mettre les erreurs ici
            echo "Ce livre existe déjà en base de données";
        }

    }
}