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
            unset($_SESSION['errors']);
        }

        $this->render('list-users', [
            'users' => $users,
            'errors' => $errors
        ]);

    }

    public function profile(int $id) : void
    {

        $um = new UserManager();

        //on cherche les infos du profil
        if (!empty($_SESSION['user_id'])) {
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

        if (!empty($_SESSION['user_id'])) {
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


    }

    public function checkUpdateUser(int $id): void
    {
        $_SESSION["errors"] = [];
        $um = new UserManager();

        if (!empty($_SESSION['user_id'])) {

            $tokenManager = new CSRFTokenManager();

            if ($_SESSION['csrf_token'] && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {

                if ($_SESSION['user_id'] === $id) {

                    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm-password"]) && !empty($_POST["intro"])) {

                        if ($_POST["password"] === $_POST["confirm-password"]) {

                            $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{12,}$/';

                            if (!preg_match($password_pattern, $_POST["password"])) {

                                $user = $um->findById($id);
                                if ($user) {
                                    $user->setUsername(htmlspecialchars($_POST["username"]));
                                    //va provoquer une erreur en base de données puisque son email sera déjà enregistré
                                    // -> j'ai mis l'email en readonly
                                    //$user_email = $_POST["email"];
                                    //$existing_email = $um->findByEmail($user_email);
                                    //if ($existing_email === null)
                                    //Peut-pêtre ajouté une fonction pour supprimer l'email seulement ? puis faire la vérification 's'il existe déjà' ?
                                    $user->setPassword(password_hash($_POST["password"], PASSWORD_BCRYPT));
                                    $user->setIntro(htmlspecialchars($_POST["intro"]));
                                } else {
                                    $_SESSION["errors"]["user"] = "User not found";
                                    //$this->redirect('index.php?route=update-user&id='.$id);
                                    exit();
                                }
                            } else {
                                $_SESSION["errors"]["password"] = "Password is not strong enough.";
                                //$this->redirect('index.php?route=update-user&id='.$id);
                                exit();
                            }
                        }  else {
                            $_SESSION["errors"]['password'] = "Passwords do not match.";
                            //$this->redirect('index.php?route=update-user&id='.$id);
                            exit();
                        }
                    } else {
                        $_SESSION["errors"]["fields"] = "Missing fields";
                        //$this->redirect('index.php?route=update-user&id='.$id);
                        exit();
                    }

                } else {
                    $_SESSION["errors"]["access denied"] = "You're not allowed to update this user.";
                    $this->redirect('home');
                    exit();
                }
            } else {
                $_SESSION["errors"]["csrf_token"] = "Invalid csrf token.";
                $this->redirect('home');
                exit();
            }

        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $this->redirect('home');
            exit();
    }



    }
     public function deleteUser(int $userId) : void {

        $um = new UserManager();
         //Ou à un membre de l'administration
        if ($_SESSION['id'] === $userId) {
            $tokenManager = new CSRFTokenManager();
            if($_SESSION['csrf_token'] !== $tokenManager->validateCSRFToken($_POST['csrf_token'])) {
                $um->delete($userId);
                $this->redirect("home");
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "Access denied";
            $this->redirect("home");
        }
     }

}