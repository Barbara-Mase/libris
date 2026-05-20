<?php

class AuthController extends AbstractController
{


    public function createUser(): void
    {
        if (!empty($_SESSION["errors"])) {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('create-user', [
                "errors" => $errors
            ]);
        } else {
            $this->render('create-user', [

            ]);
        }
    }

    public function checkCreateUser(): void
    {
        $_SESSION["errors"] = [];

        //if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"] && !empty($_POST["confirm-password"])) {
        // tous les champs sont remplis
        $um = new UserManager();
        //$user = $um->findByEmail($_POST["email"]);

        //if ($user === null) {
        $user = new User($_POST["username"], $_POST["email"], $_POST["password"], $_POST["intro"]);
        $date = new DateTimeImmutable();
        $user->setRegistrationDate($date);
        $um->create($user);
        //penser à régler la timezone


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

    public function updateUser(int $id): void
    {
        $um = new UserManager();
        $user = $um->findById($id);

        if (!empty($_SESSION["errors"])) {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('update-user', [
                'user' => $user,
                "errors" => $errors
            ]);
        } else {
            $this->render('update-user', [
                'user' => $user
            ]);
        }
    }

    public function checkUpdateUser(int $id): void
    {
        //$_SESSION["errors"];


        $um = new UserManager();

        if (!empty($_POST["username"] && $_POST["email"] && $_POST["password"] && $_POST["confirm-password"] && $_POST["intro"])) {

            // Vérifier si l'email appartient déjà à un autre utilisateur
//            $existingUser = $um->findByEmail($_POST["email"]);
//
//            if ($existingUser === null || $existingUser->getId() === $id) {
            $user = new User($_POST["username"], $_POST["email"], $_POST["password"], $_POST["intro"]);
            $user->setId($id);


            //$ret =
            $um->update($user);

        }

//                if ($ret) {
//                    unset($_SESSION["errors"]);
//                    header("Location: index.php?route=list-users");
//                } else {
//                    $_SESSION["errors"][] = "La mise à jour a échoué lors de l'écriture dans la base de données.";
//                    header("Location: index.php?route=update-user&id=$id");
//                }
//            } else {
//                $_SESSION["errors"][] = "Un utilisateur avec cet email existe déjà.";
//                header("Location: index.php?route=update-user&id=$id");
//            }
//
//        } else {
//            $_SESSION["errors"][] = "Au moins un champ obligatoire est manquant.";
//            header("Location: index.php?route=update-user&id=$id");
//        }
//    }
    }

    public function delete(int $id) : void
    {
        $um = new UserManager();
        $um->delete($id);
        header("Location: index.php?route=list-users");
    }

    public function login() : void {

        $this->render('login', []);
    }

    public function checkLogin(): void {

        $_SESSION["errors"] = [];
        $um = new UserManager();
        $user = $um->findByEmail($_POST["email-login"]);

        if(!empty($user)) {
            if($user->getPassword() === $_POST["password-login"]) {
                $_SESSION["errors"] = [];
                //problème avec la session, incomplete_class_object (voir objet désérialisé)
                $_SESSION["user"] = $user;

                echo "Welcome " . $_SESSION["user"]->getUsername();
                header("Location: index.php?route=profile&id=" . $_SESSION["user"]->getId());
            }
            else {
                $_SESSION["errors"] = [];
                $_SESSION["errors"]["password"] = "Wrong password";
            }
        } else {
            $_SESSION["errors"] = [];
            $_SESSION["errors"]["email"] = "Wrong email";
        }
    }

    public function logout(): void {
        unset($_SESSION["user"]);
    }


}