<?php
class AbstractController
{

    public function __construct() {

    }
    protected function render(string $template, array $data = []): void
    {
        if(!empty($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION['user_id'];
            $data[] = $sessionUserId;
            $um = new UserManager();
            $user = $um->findById($sessionUserId);
            if (!empty($user)) {
                if($user->getRole() === 'ADMIN') {
                    $isAdmin = true;
                    $data[] = $isAdmin;
                }
            }
        }

        extract($data);

        require "templates/layout.phtml";
    }

    protected function adminRender(string $template, array $data = []): void {

        if(!empty($_SESSION['user_id'])) {
            $sessionUserId = $_SESSION['user_id'];
            $um = new UserManager();
            $user = $um->findById($sessionUserId);
            if (!empty($user)) {
                if($user->getRole() === 'ADMIN') {
                    $isAdmin = true;
                    $data[] = $isAdmin;
                }
            }
        }

        extract($data);

        require "templates/admin/admin-layout.phtml";
    }

    protected function redirect(string $route): void
    {
        header('Location: /libris/?' . $route);
        exit();
    }
}