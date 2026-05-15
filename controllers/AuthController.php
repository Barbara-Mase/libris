<?php

class AuthController extends AbstractController {




    public function createUser(): void
    {
        if (!empty($_SESSION["errors"])) {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('registration', [
                "errors" => $errors
            ]);
        } else {
            $this->render('registration', [

            ]);
        }
    }
    public function checkCreateUser() : void
    {
        $_SESSION["errors"] = [];

        //if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
            // tous les champs sont remplis
            $um = new UserManager();
            //$user = $um->findByEmail($_POST["email"]);

            //if ($user === null) {
                $user = new User($_POST["username"], $_POST["email"], $_POST["password"], $_POST["intro"]);
                $date = new DateTimeImmutable();
                $user->setRegistrationDate($date);
                $um->create($user);


                $this->render('home', []);

//                if ($ret) {
//                    unset($_SESSION["errors"]);
//                    header("Location: index.php?route=home");
//                } else {
//                    $_SESSION["errors"][] = "La création a échoué lors de l'écriture dans la base de données.";
//                    header("Location: index.php?route=registration");
//                }
//            } else {
//                $_SESSION["errors"][] = "Un utilisateur avec cet email existe déjà.";
//                header("Location: index.php?route=registration");
//            }
//        } else {
//            $_SESSION["errors"][] = "Au moins un champ obligatoire est manquant.";
//            header("Location: index.php?route=registration");
//        }
    }
}