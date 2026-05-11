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
        $_SESSION["errors"] = [];

        $bm = new BookManager();
        $book = $bm->findByKey($_POST['key']);

        if($book === null) {
            $newBook = new Book($_POST['key'], $_POST["title"], $_POST["author"], $_POST["publish_year"], $_POST["cover_id"]);
            $bm->createBook($newBook);
        }

    }
}