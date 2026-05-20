<?php

require "vendor/autoload.php";

//Quand on appelle session_start() avant, problème d'objet désérialisé (incomplete class object)
session_start();

// charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->handleRequest($_GET);


