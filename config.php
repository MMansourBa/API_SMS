<?php
    require_once __DIR__ . '/vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $servername = $_ENV['DB_SERVERNAME'];
    $username_db = $_ENV['DB_USERNAME'];
    $password_db = $_ENV['DB_PASSWORD'];
    $dbname = $_ENV['DB_NAME'];
?>
