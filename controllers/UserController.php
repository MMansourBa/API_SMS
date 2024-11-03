<?php
require_once 'models/UserModel.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function register($username, $phone_number, $password, $confirm_password) {
        if ($password !== $confirm_password) {
            header("Location: register.php?error=Les mots de passe ne correspondent pas");
            exit();
        }

        if ($this->model->checkUserExists($phone_number, $username)) {
            header("Location: register.php?error=Nom d'utilisateur ou numéro déjà utilisé");
            exit();
        }

        if ($this->model->register($username, $phone_number, $password)) {
            header("Location: login.php");
        } else {
            header("Location: register.php?error=Erreur lors de l'inscription");
        }
    }

    public function login($phone_number, $password) {
        $user = $this->model->login($phone_number, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            header("Location: sms_views.php?token=" . $user['token']);
        } else {
            header("Location: login.php?error=Identifiants incorrects");
        }
    }
}
