<?php

class BookController extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function detailBook(int $id) : void {

        $bm = new BookManager();
        $book = $bm->findOne($id);

        $this->render('book', [
            'book' => $book
        ]);
    }

    public function checkCreate() : void

    {

        //Ajouter une condition pour vérifier si la clé est déjà présente ou non en BDD
        $book = new Book($_POST['key'], $_POST["title"], $_POST["author"], $_POST["publish_year"], $_POST["cover_id"]);
        //$book = new Book('key_example', 'La passion des croquettes', 'Cajou', '2003', '0101');
        $bm = new BookManager();
        $bm->createBook($book);
        var_dump($book);




    }
}