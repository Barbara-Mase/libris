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

        // tous les champs sont remplis
        if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"] && !empty($_POST["confirm-password"]))) {
            // les deux mots de passe correspondent
            if ($_POST["password"] === $_POST["confirm-password"]) {

                $um = new UserManager();
                //cherche l'utilisateur par l'email
                $user = $um->findByEmail($_POST["email"]);

                //Si l'utilisateur n'existe pas
                if ($user === null) {
                    $user = new User($_POST["username"], $_POST["email"], $_POST["password"], $_POST["intro"]);
                    $date = new DateTime();
                    $user->setRegistrationDate($date);
                    $newUser = $um->create($user);
                    //penser à régler la timezone

                    if ($newUser) {
                        unset($_SESSION["errors"]);
                        header("Location: index.php?route=login");
                        //ajouter alerte précisant que l'user a été créer
                    } else {
                        $_SESSION["errors"][] = "Error writing to database";
                        header("Location: index.php?route=create-user");
                    }
                } else {
                    $_SESSION["errors"][] = "This email is already registered";
                    header("Location: index.php?route=create-user");
                }

            } else {
                $_SESSION["errors"][] = "Passwords do not match";
                header("Location: index.php?route=create-user");
            }

        } else {
            $_SESSION["errors"][] = "At least one field is empty";
            header("Location: index.php?route=create-user");
        }
    }

    public function updateUser(int $id): void
    {
        $um = new UserManager();
        $user = $um->findById($id);

        $userId = $user->getId();
        $sessionId = $_SESSION["user"]->getId();

        if ($userId === $sessionId)  {
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

    }

    public function checkUpdateUser(int $id): void
    {
        $_SESSION["errors"] = [];

        $um = new UserManager();

        if (!empty($_POST["username"] && $_POST["email"] && $_POST["password"] && $_POST["confirm-password"] && $_POST["intro"])) {

            if ($_POST["password"] === $_POST["confirm-password"]) {
                //Vérifier si l'email appartient déjà à un autre utilisateur
                $existingUser = $um->findByEmail($_POST["email"]);

                if ($existingUser === null) {
                    $user = new User($_POST["username"], $_POST["email"], $_POST["password"], $_POST["intro"]);
                    $user->setId($id);
                    $userUpdate = $um->update($user);

                    if ($userUpdate) {
                        unset($_SESSION["errors"]);
                        header("Location: index.php?route=home");
                        //ajouter une alerte qui dit que la modification a été prise en compte

                    } else {
                        $_SESSION["errors"][] = "Error writing to database";
                        header("Location: index.php?route=update-user&id=$id");
                    }
                } else {
                    $_SESSION["errors"][] = "This email is already registered";
                    header("Location: index.php?route=update-user&id=$id");
                }
            } else {
                $_SESSION["errors"][] = "Passwords do not match";
                header("Location: index.php?route=update-user&id=$id");
            }
        } else {
            $_SESSION["errors"][] = "At least one field is empty";
            header("Location: index.php?route=update-user&id=$id");
        }
    }

    public function delete(int $id) : void
    {
        $um = new UserManager();
        $um->delete($id);
        header("Location: index.php?route=list-users");
    }

    public function login() : void
    {
        $_SESSION["errors"] = [];

        if (!empty($_SESSION['user'])) {
            $_SESSION['errors']['loggedin'] = "You are already logged in";
            $errors = $_SESSION["errors"];
            unset($_SESSION['errors']);
            echo 'Vous êtes déjà connecté(e)';
            $this->render('login', [
                "errors" => $errors
            ]);
        } else {
            $this->render('login', []);
        }

        //ajouter alerte condition 'déjà connecté'
    }

    public function checkLogin(): void {

        $um = new UserManager();
        $user = $um->findByEmail($_POST["email-login"]);

        if(!empty($user)) {
            if ($user->getPassword() === $_POST["password-login"]) {
                $_SESSION["user"] = $user;
                header("Location: index.php?route=profile&id=" . $_SESSION["user"]->getId());
                echo "Welcome " . $_SESSION["user"]->getUsername();
            }
            else {
                $_SESSION["errors"] = [];
                $_SESSION["errors"]["password"] = "Wrong password";
                header("Location: index.php?route=login");
            }
        } else {
            $_SESSION["errors"] = [];
            $_SESSION["errors"]["email"] = "Wrong email";
            header("Location: index.php?route=login");
        }
    }

    public function logout(): void {
        unset($_SESSION["user"]);
        echo 'Vous êtes déconnecté';
    }


}