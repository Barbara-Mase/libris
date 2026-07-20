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
        if (empty($_SESSION["user_id"])) {
            if (!empty($_POST["username"])
                && !empty($_POST["email"])
                && !empty($_POST["password"])
                && !empty($_POST["confirm-password"])
                && !empty($_POST["intro"]))
            {
                //Vérification de la validité du token CSRF
                $tokenManager = new CSRFTokenManager();
                if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token']))
                {
                    /*Vérification que le mot de passe et la confirmation du mot de passe sont identiques afin de prévenir le fait
                    que les utilisateurs fassent des fautes de frappe*/
                    if ($_POST["password"] === $_POST["confirm-password"])
                    {
                        /*$passwordpattern impose au moin 1 minuscule, 1 majuscule, 1 chiffre,
                        1 caractère spécial et 12 caractères minimum*/
                        $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{12,}$/';
                        // La fonction preg_match vérifie que le mot de passe entré par l'utilisateur respecte le pattern
                        if (preg_match($password_pattern, $_POST["password"]))
                        {
                            $um = new UserManager();
                            //la fonction filter_var vérifie que l'email est valide
                            if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                                $user = $um->findByEmail($_POST["email"]);
                                if ($user === null) {
                                    /*La fonction htmlspecialchars transforme les potentiels
                                    caractères spéciaux afin de se protéger des injections de code*/
                                    $username = htmlspecialchars($_POST["username"]);
                                    //Ajouter le hash de l'email
                                    $email = $_POST["email"];
                                    /*password_hash permet de chiffré le mot de passe en base de données
                                    pour protéger les comptes utilisateurs*/
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
                            //Ici la date actuelle est assignée à $lastLogin
                            $lastLogin = new DateTime();
                            //Cette date est ensuite assignée aux données de l'utilisateur
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

    public function deleteUser($userId) : void {

        $_SESSION["errors"] = [];
        $um = new UserManager();

        if (empty($_SESSION["user_id"])) {
            $_SESSION["errors"]["login"] = "You must be logged in to delete user.";
            $this->redirect("route=login");
        }

        if($_SESSION["user_id"] !== $userId) {
            $_SESSION["errors"]["login"] = "You do not have permission to delete user.";
            $this->redirect("route=home");
        }

        $tokenManager = new CSRFTokenManager();

        if (!isset($_POST['csrf_token']) || !$tokenManager->validateCSRFToken($_POST['csrf_token'])) {
            $_SESSION["errors"]["csrf_token"] = "Invalid CSRF token";
            $this->redirect("route=home");
        }

        $um->delete($userId);
        $this->redirect("route=home");
    }
}