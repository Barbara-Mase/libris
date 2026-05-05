<?php


class HomeController extends AbstractController

{

    public function home(): void
    {

        $this->render("home", []);
    }

}