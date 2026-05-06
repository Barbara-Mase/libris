<?php

class BookListController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function detailList(int $id) : void {


        $blm = new BookListManager();
        $list = $blm->findOne($id);

        $this->render('list', [
            'list' => $list
        ]);

    }
}