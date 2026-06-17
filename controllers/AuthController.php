<?php

class AuthController extends AbstractController
{


    public function createUser(): void
    {
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION["errors"]);

        $this->render('user/create-user', [
            "errors" => $errors
        ]);
    }

    public function checkCreateUser(): void
    {
        //Les exits ?

        if (empty($_SESSION["user_id"])) {

            if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm-password"]) && !empty($_POST["intro"]))
            {
                $tokenManager = new CSRFTokenManager();

                if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token']))
                {
                    // les deux mots de passe correspondent
                    if ($_POST["password"] === $_POST["confirm-password"])
                    {
                        //Impose au moint 1 minuscule, 1 majuscule, 1 chiffre, 1 caractère spécials et 12 caractères minimum
                        $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{12,}$/';

                        if (preg_match($password_pattern, $_POST["password"]))
                        {
                            $um = new UserManager();
                            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                                $user = $um->findByEmail($_POST["email"]);

                                //Si l'utilisateur n'existe pas
                                if ($user === null) {

                                    $username = htmlspecialchars($_POST["username"]);
                                    //Ajouter le hash de l'email
                                    $email = $_POST["email"];
                                    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                                    $intro = htmlspecialchars($_POST["intro"]);
                                    $user = new User($username, $email, $password, $intro);
                                    $lastLogin = new DateTime();
                                    $user->setLastLogin($lastLogin);
                                    $date = new DateTime();
                                    $user->setRegistrationDate($date);
                                    $user->setRole('USER');
                                    $newUser = $um->create($user);
                                    //penser à régler la timezone
                                    if ($newUser) {
                                        unset($_SESSION["errors"]);
                                        $this->redirect("route=login");
                                        //ajouter alerte précisant que l'user a été créé
                                    } else {
                                        $_SESSION["errors"]["register"] = "Error writing to database";
                                        $this->redirect("route=create-user");
                                    }

                                } else {
                                    $_SESSION["errors"]["register"] = "This email is already registered";
                                    $this->redirect("route=create-user");

                                }
                            } else {
                                $_SESSION['errors']['csrf_token'] = "Error writing to csrf token";
                                $this->redirect("route=create-user");

                            }
                        } else
                        {
                            $_SESSION["errors"]["register"] = "Password is not strong enough";
                            $this->redirect("route=create-user");
                        }
                    }
                    else
                    {
                        $_SESSION["errors"]["register"] = "Passwords do not match";
                        $this->redirect("route=create-user");

                    }
                }
                else
                {
                    $_SESSION["errors"]["CSRF_token"] = "Invalid CSRF token";
                    $this->redirect("route=create-user");

                }
            }
            else
            {
                $_SESSION["errors"]["register"] = "Missing fields";
                $this->redirect("route=create-user");

            }

        } else {
            $_SESSION["errors"]["create-user"] = "You need to be logged out to create an account.";
            $this->redirect("route=create-user");
        }

    }



    public function login() : void
    {

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION["errors"]);

        $this->render('user/login', [
            'errors' => $errors
        ]);

    }

    public function checkLogin(): void
    {
        $_SESSION["errors"] = [];

        if (!empty($_POST["email-login"]) && !empty($_POST["password-login"]))
        {
            $tokenManager = new CSRFTokenManager();

            if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

                if (empty($_SESSION["user_id"]))
                {
                    $um = new UserManager();
                    $user = $um->findByEmail($_POST["email-login"]);

                    if ($user !== null)
                    {
                        if (password_verify($_POST["password-login"], $user->getPassword()))
                        {
                            $_SESSION["user_id"] = $user->getId();
                            $lastLogin = new DateTime();
                            $user->setLastLogin($lastLogin);
                            $um = new UserManager;
                            $um->update($user);
                            $this->redirect("route=profile&id=" . $_SESSION["user_id"]);

                        }
                        else
                        {
                            //L'erreur est vague pour qu'un pirate ne puisse pas déterminer d'où elle vient
                            $_SESSION["errors"]["login"] = "Invalid email or password";
                            $this->redirect("route=login");

                        }
                    }
                    else
                    {
                        $_SESSION["errors"]["login"] = "Invalid email or password";
                        $this->redirect("route=login");

                    }
                }
                else
                {
                    $_SESSION["errors"]['login'] = "You are already logged in";
                    $this->redirect("route=home");

                }
            }
            else
            {
                $_SESSION["errors"]['csrf_token'] = "Invalid CSRF token";
                $this->redirect("route=login");


            }
        }
        else
        {
            $_SESSION["errors"]["login"] = "Missing fields";
            $this->redirect("route=login");

        }
    }

    public function logout(): void {
        unset($_SESSION["user_id"]);
        session_destroy();
        session_start();
        session_regenerate_id(true);
        $this->redirect("index.php?route=home");
    }
}