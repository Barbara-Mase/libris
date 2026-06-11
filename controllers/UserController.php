<?php


class UserController extends AbstractController {

    public function __construct(){
        parent::__construct();
    }


    public function list() : void
    {
        $errors = [];
        $um = new UserManager();
        $users = $um->findAll();

        if(empty($users)){
            $_SESSION['errors']['users_list'] = 'No users found';
            $errors = $_SESSION['errors'];
        }

        $this->render('list-users', [
            'users' => $users,
            'errors' => $errors
        ]);

    }

    public function profile(int $id) : void
    {

        $_SESSION["errors"] = [];
        $um = new UserManager();

        //on cherche les infos du profil
        if ($id) {
            $user = $um->findById($id);
            if ($user) {
                unset($_SESSION["errors"]);
            } else {
                $_SESSION["errors"]["access_denied"] = "User not found";
                $this->redirect('home');
                exit();
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $this->redirect('home');
            exit();
        }

        $bm = new BookManager();
        //bookIdList est un tableau de tableau donc on boucle deux fois
        $bookIdList = $bm->findBooksUsers($id);
        $booksList = [];

        if ($bookIdList) {
            foreach ($bookIdList as $arrayBookId) {
                foreach ($arrayBookId as $bookId) {
                    $book = $bm->findById($bookId);
                    $booksList[] = $book;
                }
            }
        }

        $cm = new CommentManager();
        $comments = $cm->findCommentByUserId($id);
        //s'il n'y a pas d'erreur, on envoie tout à la vue
        $this->render('user', [
            'user' => $user,
            'booksList' => $booksList,
            'comments' => $comments
        ]);
    }

    public function updateUser(int $id): void
    {
        $um = new UserManager();
        $user = $um->findById($id);

        $userId = $user->getId();
        $sessionId = $_SESSION["user_id"];

        if (empty($_SESSION['user_id'])) {
            if ($userId === $sessionId) {
                $this->render('update-user', [
                    'user' => $user,
                ]);
            } else {
                $_SESSION["errors"]["access_denied"] = "You are not allowed to update this user.";
                $this->redirect('home');
                exit();
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $this->redirect('home');
            exit();
        }

        /////////
        if ($userId === $sessionId)  {
            $tokenManager = new CSRFTokenManager();
            if ($_SESSION['CSRFToken'] && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {
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
     public function deleteUser(int $userId) : void {

        $um = new UserManager();
         //Ou à un membre de l'administration
        if ($_SESSION['id'] === $userId) {
            $tokenManager = new CSRFTokenManager();
            if($_SESSION['CSRFToken'] !== $tokenManager->validateCSRFToken($_POST['csrf_token'])) {
                $um->delete($userId);
                $this->redirect("home");
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "Access denied";
            $this->redirect("home");
        }
     }

}