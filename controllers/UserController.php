<?php


class UserController extends AbstractController {

    public function __construct(){
        parent::__construct();
    }


    public function list() : void
    {
        $um = new UserManager();
        $users = $um->findAll();

        if(empty($users)){
            $_SESSION['errors']['users_list'] = 'No users found';
        }

        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        $this->render('user/users-list', [
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
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $this->redirect('home');
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
        $comments = $cm->findCommentsByUserId($id);
        //s'il n'y a pas d'erreur, on envoie tout à la vue
        $this->render('user/user', [
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
        //Par défaut, renvoie un tableau vide
        $errors = $_SESSION['errors'] ?? [];

        if (!empty($_SESSION['user_id'])) {
            if ($userId === $sessionId) {
                $this->render('user/update-user', [
                    'user' => $user,
                    'errors' => $errors
                ]);
            } else {
                $_SESSION["errors"]["access_denied"] = "You are not allowed to update this user.";
                $this->redirect('home');
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $this->redirect('home');
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

                            if (preg_match($password_pattern, $_POST["password"])) {

                                $user = $um->findById($id);

                                if ($user) {
                                    $user->setUsername(htmlspecialchars($_POST["username"]));
                                    $user->setPassword(password_hash($_POST["password"], PASSWORD_BCRYPT));
                                    $user->setIntro(htmlspecialchars($_POST["intro"]));

                                    if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

                                        $newEmail = $_POST["email"];

                                        if ($newEmail !== $user->getEmail()) {
                                            $existingEMail = $um->findByEmail($newEmail);

                                            if ($existingEMail === null) {
                                                $user->setEmail($newEmail);
                                            } else {
                                                $_SESSION["errors"]["email"] = "This email is already in use.";
                                                $this->redirect('route=update-user&id=' . $id);
                                            }
                                        }
                                        $user->setId($id);
                                        $um->update($user);
                                        $this->redirect('route=profile&id=' . $id);
                                    } else {
                                        $_SESSION["errors"]["email"] = "Please enter a valid email address.";
                                        $this->redirect('route=update-user&id=' . $id);
                                    }
                                } else {
                                    $_SESSION["errors"]["user"] = "User not found";
                                    $this->redirect('route=update-user&id=' . $id);

                                }
                            } else {
                                $_SESSION["errors"]["password"] = "Password is not strong enough.";
                                $this->redirect('route=update-user&id=' . $id);

                            }
                        }  else {
                            $_SESSION["errors"]['password'] = "Passwords do not match.";
                            $this->redirect('route=update-user&id=' . $id);

                        }
                    } else {
                        $_SESSION["errors"]["fields"] = "Missing fields";
                        $this->redirect('route=update-user&id=' . $id);

                    }

                } else {
                    $_SESSION["errors"]["access denied"] = "You're not allowed to update this user.";
                    $this->redirect('home');

                }
            } else {
                $_SESSION["errors"]["csrf_token"] = "Invalid csrf token.";
                $this->redirect('home');

            }

        } else {
            $_SESSION["errors"]["access_denied"] = "You must be logged in to view this page.";
            $this->redirect('home');

    }



    }
    public function deleteUser(int $userId) : void
    {
        $um = new UserManager();

        if ($_SESSION['user_id'] === $userId) {
            $tokenManager = new CSRFTokenManager();
            if ($_SESSION['csrf_token'] && $tokenManager->validateCSRFToken($_POST['csrf_token'])) {
                $um->delete($userId);
                session_destroy();
                $_SESSION['success']['account_deleted'] = "Your account has been successfully deleted.";
                $this->redirect("home");
            } else {
                $_SESSION["errors"]["csrf_token"] = "Invalid CSRF token.";
                $this->redirect("home");
            }
        } else {
            $_SESSION["errors"]["access_denied"] = "Access denied.";
            $this->redirect("home");
        }
    }

}