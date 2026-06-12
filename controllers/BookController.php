<?php

class BookController extends AbstractController {

    public function __construct() {
        parent::__construct();
    }

    public function detailBook(int $bookId) : void {

        unset($_SESSION["book_id"]);
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        $comments = [];

        $bm = new BookManager();
        $book = $bm->findById($bookId);

        $cm = new CommentManager();
        $comments = $cm->findCommentsByBookId($bookId);

        if($book) {
            $this->render('detail-book', [
                'book' => $book,
                "comments" => $comments,
                'errors' => $errors
            ]);
            $_SESSION["book_id"] = $bookId;
        } else {
            $_SESSION["errors"]["book"] = "Book not found";
            $this->redirect("route=home");
        }

    }


    //à ajouter : une vérification des erreurs potentiels
    public function checkCreate() : void

    {
        // Ajouter une gestion d'erreur si possible

        //Vérifier si le livre existe en bdd
        $bm = new BookManager();
        $book = $bm->findByKey($_POST['key']);

        //s'il n'existe pas on le créé
        if(!$book) {
            $newBook = new Book($_POST['key'], htmlspecialchars($_POST["title"]), htmlspecialchars($_POST["author"]), $_POST["publish_year"], $_POST["cover_id"]);
            //on assigne le nouveau a une variable book, le livre est retourné par la fonction create
            $book = $bm->createBook($newBook);
        }
        //On récupère l'id du livre qu'on envoie à javascript via un json_encode()
        $book_id = $book->getBookId();
        echo json_encode($book_id);
    }

    //en chantier
    public function addToList() : void {

        //Gérer les erreurs 'user not loggedin"
        $_SESSION['errors'] = [];
        $bm = new BookManager();

        if ($_SESSION['user_id']) {
            $userId = $_SESSION['user_id'];
            if($_SESSION['book_id']) {
                $bookId = $_SESSION['book_id'];
                $bm->addBookUser($bookId, $userId);
            } else {
                $_SESSION["errors"]["book"] = "Book not found";
                $this->redirect("route=home");
            }
        } else {
            $_SESSION["errors"]["book"] = "You must be logged in to add a book to your list";
            echo json_encode(['success' => false]);
        }

    }

    public function removeBookFromList(int $bookId) : void
    {

        $bm = new BookManager();

        if (!empty($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $bm->removeBookUser($bookId, $userId);
            $this->redirect("route=profile&id=" . $userId);
        } else {
            $_SESSION["errors"]["book"] = "Something went wrong";
            $this->redirect("route=home");
        }

    }
    public function deleteBook(int $bookId) : void {

        $bm = new BookManager();
        //Il faut que ce soit un admin qui le fasse
        $bm->deleteBook($bookId);
    }

}