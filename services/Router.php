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
                echo "List";
            }
            else if($get['route'] === "profile") {
                $uc = new UserController();
                $uc->details(intval($get['id']));
            } else if ($get['route'] === "create-user") {
                $uc = new UserController();
                $uc->create();
            } else if ($get['route'] === "check-create-user") {
                $uc = new UserController();
                $uc->checkCreate();
            }
            else {
                echo "Cette page n existe pas";
            }
        } else {
            $hm = new HomeController();
            $hm->home();
        }

    }

}
