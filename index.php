<?php
session_start();
require_once './controllers/UserController.php';

$action = $_GET['action'] ?? null;
$authController = new UserController();

switch ($action) {
    case 'login':
        $authController->login($_POST['identifier'], $_POST['password']);
        break;
    case 'register':
        $authController->register($_POST['username'], $_POST['phone_number'], $_POST['password'], $_POST['confirm_password']);
        break;
    default:
        header("Location: views/login.php");
        break;
}
