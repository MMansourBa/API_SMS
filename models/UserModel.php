<?php
require_once 'config/config.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($username, $phone_number, $password) {
        $stmt = $this->db->prepare("INSERT INTO users (username, phone_number, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $phone_number, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function checkUserExists($phone_number, $username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE phone_number = ? OR username = ?");
        $stmt->execute([$phone_number, $username]);
        return $stmt->fetch();
    }

    public function login($phone_number, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE phone_number = ? OR username = ?");
        $stmt->execute([$phone_number, $phone_number]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Générer un token et mettre à jour dans la table
            $token = bin2hex(random_bytes(16));
            $this->db->prepare("UPDATE users SET token = ? WHERE id = ?")->execute([$token, $user['id']]);
            $user['token'] = $token;
            return $user;
        }

        return false;
    }
}
