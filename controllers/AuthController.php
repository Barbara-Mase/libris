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

        //ajouter les exit
        $_SESSION["errors"] = [];

        if (empty($_SESSION["user_id"])) {

            if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm-password"] && !empty($_POST["intro"])))
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
                            $user = $um->findByEmail($_POST["email"]);

                            //Si l'utilisateur n'existe pas
                            if ($user === null)
                            {
                                if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                                    $username = htmlspecialchars($_POST["username"]);
                                    $email = htmlspecialchars($_POST["email"]);
                                    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                                    $intro = htmlspecialchars($_POST["intro"]);
                                    $user = new User($username, $email, $password, $intro);
                                    $date = new DateTime();
                                    $user->setRegistrationDate($date);
                                    $newUser = $um->create($user);
                                    //penser à régler la timezone
                                    if ($newUser)
                                    {
                                        unset($_SESSION["errors"]);
                                        $this->redirect("index.php?route=login");
                                        //ajouter alerte précisant que l'user a été créé
                                    }
                                    else
                                    {
                                        $_SESSION["errors"]["register"] = "Error writing to database";
                                        $this->redirect("index.php?route=create-user");
                                    }
                                } else {
                                    $_SESSION["errors"]["email"] = "Invalid email";
                                    $this->redirect("index.php?route=create-user");
                                }

                            } else
                            {
                                $_SESSION["errors"]["register"] = "This email is already registered";
                                $this->redirect("index.php?route=create-user");
                            }
                        } else
                        {
                            $_SESSION["errors"]["register"] = "Password is not strong enough";
                            $this->redirect("index.php?route=create-user");
                        }
                    }
                    else
                    {
                        $_SESSION["errors"]["register"] = "Passwords do not match";
                        $this->redirect("index.php?route=create-user");
                    }
                }
                else
                {
                    $_SESSION["errors"]["CSRF_token"] = "Invalid CSRF token";
                    $this->redirect("index.php?route=create_user");
                }
            }
            else
            {
                $_SESSION["errors"]["register"] = "Missing fields";
                $this->redirect("index.php?route=create-user");
            }

        } else {
            $_SESSION["errors"]["create-user"] = "You need to be logged out to create an account.";
            $this->redirect("index.php?route=create-user");
            exit;
        }

    }

    public function updateUser(int $id): void
    {
        $um = new UserManager();
        $user = $um->findById($id);

        $userId = $user->getId();
        $sessionId = $_SESSION["user_id"];

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

        if (!empty($_SESSION["user_id"])) {

            if (!empty($_POST["username"] && $_POST["email"] && $_POST["password"] && $_POST["confirm-password"] && $_POST["intro"]))
            {
                $tokenManager = new CSRFTokenManager();
                if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token']))
                {
                        $session_user_id = $_SESSION['user_id'];
                        if ($session_user_id === $id)
                        {
                            //va provoquer une erreur en base de données puisque son email sera déjà enregistré -> j'ai mis l'email en readonly
                            //$user_email = $_POST["email"];
                            //$existing_email = $um->findByEmail($user_email);
                            //if ($existing_email === null)
                            //{
                            $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{12,}$/';

                            if (preg_match($password_pattern, $_POST["password"]))
                            {
                                if ($_POST["password"] === $_POST["confirm-password"])
                                {
                                    $username = htmlspecialchars($_POST["username"]);
                                    $email = htmlspecialchars($_POST["email"]);
                                    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                                    $intro = htmlspecialchars($_POST["intro"]);
                                    $updated_user = new User($username, $email, $password, $intro);
                                    $updated_user->setId($id);
                                    $ret = $um->update($updated_user);

                                    if ($ret)
                                    {
                                        unset($_SESSION["errors"]);
                                        $this->redirect("index.php?route=profile&id=" . $id);
                                    }
                                    else
                                    {
                                        $_SESSION["errors"]["register"] = "Error writing to database";
                                        $this->redirect("index.php?route=update-user&id=" .$id);
                                    }
                                }
                                else
                                {
                                    $_SESSION["errors"]["password"] = "Passwords do not match";
                                    $this->redirect("index.php?route=update-user&id=" . $id);
                                }

                            } else {
                                $_SESSION["errors"]["password"] = "Password is not strong enough";
                                $this->redirect("index.php?route=update-user&id=" . $id);
                            }
                            //}
                            //else
                            //{
                            //$_SESSION["errors"]["register"] = "This email is already registered";
                            //$this->redirect("Location: index.php?route=update-user");
                            //}
                        } else {
                            $_SESSION["errors"]["register"] = "Access denied";
                            $this->redirect("index.php?route=home");
                        }
                }
                else
                {
                    $_SESSION["errors"]["CSRF_token"] = "Invalid CSRF token";
                    $this->redirect("index.php?route=update_user&id=" . $id);
                }
            }
            else
            {
                $_SESSION["errors"][] = "Missing fields";
                $this->redirect("index.php?route=update-user&id=" . $id);
            }
        } else {
            $_SESSION["errors"]["register"] = "You must be logged in to update an account.";
            $this->redirect("index.php?route=home");
            exit;
        }

    }

    public function delete(int $id) : void
    {
        $um = new UserManager();
        $um->delete($id);
        $this->redirect("index.php?route=list-users");
    }

    public function login() : void
    {

        if (!empty($_SESSION["errors"])) {
            $errors = $_SESSION["errors"];
            unset($_SESSION["errors"]);
            $this->render('login', [
                "errors" => $errors
            ]);
        } else {
            $this->render('login', []);
        }

    }

    public function checkLogin(): void
    {
        $_SESSION["errors"] = [];

        //La première condition n'est pas vérifié. Quand il manque un champs, seule l'erreur "Invalid email or password" est affichée
        if (isset($_POST["email-login"]) && isset($_POST["password-login"]))
        {
            $tokenManager = new CSRFTokenManager();
            if (isset($_POST['csrf_token']) && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

                if (empty($_SESSION["user_id"]))
                {
                    $um = new UserManager();
                    $user = $um->findByEmail($_POST["email-login"]);

                    if ($user !== null)
                    {
                        //Est ce que password_verify() ne fonctionne pas car il prend en paramètre un hash ?
                        //if ($_POST["password-login"] === $user->getPassword())
                        if (password_verify($_POST["password-login"], $user->getPassword()))
                        {
                            $_SESSION["user_id"] = $user->getId();
                            $this->redirect("index.php?route=profile&id=" . $_SESSION["user_id"]);
                        }
                        else
                        {
                            //L'erreur est vague pour qu'un pirate ne puisse pas déterminer d'où elle vient
                            $_SESSION["errors"]["login"] = "Invalid email or password";
                            $this->redirect("index.php?route=login");
                        }
                    }
                    else
                    {
                        $_SESSION["errors"]["login"] = "Invalid email or password";
                        $this->redirect("index.php?route=login");
                    }
                }
                else
                {
                    $_SESSION["errors"]['login'] = "You are already logged in";
                    $this->redirect("index.php?route=home");
                }
            }
            else
            {
                $_SESSION["errors"] = "Invalid CSRF token";
                $this->redirect("index.php?route=login");

            }
        }
        else
        {
            $_SESSION["errors"]["login"] = "Missing fields";
            $this->redirect("index.php?route=login");
        }
    }

    public function logout(): void {
        unset($_SESSION["user_id"]);
        $this->redirect("index.php?route=home");
    }

}