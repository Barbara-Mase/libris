<?php


class UserController extends AbstractController {

    public function __construct(){
        parent::__construct();
    }

    //Est ce que j'ai besoin de cette fonction ?
//    public function list() : void
//    {
//        $um = new UserManager();
//        $users = $um->findAll();
//
//        // attention à twig
//        $this->render('admin/users/list.html.twig', [
//            'users' => $users
//        ]);
//    }

    public function details(int $id) : void
    {
        $um = new UserManager();
        $user = $um->findOne($id);


        // attention à twig
        $this->render('user', [
            'user' => $user
        ]);
    }



    public function update(int $id) : void
    {
        $um = new UserManager();
        $user = $um->findOne($id);

        // attention à twig
        if(!empty($_SESSION["errors"]))
        {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('admin/users/update.html.twig', [
                'user' => $user,
                "errors" => $errors
            ]);
        }
        else
        {
            // attention à twig
            $this->render('admin/users/update.html.twig', [
                'user' => $user
            ]);
        }
    }

    //NON FINI
    //ET L'ID ?
    public function checkUpdate(int $id) : void
    {
        $_SESSION["errors"] = [];

        if(!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["first_name"]) && !empty($_POST["last_name"]))
        {
            $um = new UserManager();
            $user = $um->findOne($id);
            if(!empty($user)) {
                $user = new User($_POST["email"], $_POST["password"], $_POST["first_name"], $_POST["last_name"]);
                $user->setId($_POST["id"]);
                $ret = $um->update($user);
                if ($ret) {
                    unset($_SESSION["errors"]);
                    header("Location: index.php?route=list-user");
                } else {
                    $_SESSION["errors"][] = "La modification a échoué lors de l'écriture dans la base de données.";
                    header("Location: index.php?route=update-users");
                }
            }
        }
        else
        {
            $_SESSION["errors"][] = "Au moins un champ obligatoire est manquant.";
            header("Location: index.php?route=update-user");
        }
    }




    public function delete(int $id) : void
    {
        $um = new UserManager();
        $um->delete($id);
        header("Location: index.php?route=list-users");
    }


}