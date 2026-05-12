<?php

class BookListController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showList(int $id) : void {

        // récupère la liste relative à l'utilisateur
        $blm = new BookListManager();
        $list = $blm->findOne($id);

        $this->render('list', [
            'list' => $list
        ]);

    }

    public function getBooks(int $id) : array {

        $bm = new BookManager();
        $book = $bm->findById($id);

        $blm = new BookListManager();

        $list = $blm->findOne($id);

        /*if(isset($list)) {
            $list [] = $book;
            return $list;
        } else {
            $list = new BookList();
            $blm->create($list);
        }*/


    }
}