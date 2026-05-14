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

//    public function getBooks(int $id) : array
//    {
//
//        $bm = new BookManager();
//        $book = $bm->findById($id);
//
//        $blm = new BookListManager();
//
//        $list = $blm->findOne($id);
//
//        /*if(isset($list)) {
//            $list [] = $book;
//            return $list;
//        } else {
//            $list = new BookList();
//            $blm->create($list);
//        }*/
//
//    }

public function addBookList() : void {

        //$bm = new BookManager();

        //$list = $blm->findOne($book_list_id);
        //$book = $bm->findByKey($book_key);

        //if($book) {
            $blm = new BookListManager();
            $blm->addBook(8, 1);
//        } else {
//            $bc = new BookController();
//            $bc->checkCr
        echo 'fonction du controller';




}
}