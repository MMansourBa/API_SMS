<?php
    session_start(); // Démarrer la session

    require_once '../config.php';

    // Créer la connexion
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les valeurs du formulaire
        $phone_number = $_POST['phone_number'];
        $password = $_POST['password'];

        // Préparer la requête pour vérifier les identifiants
        $sql = "SELECT * FROM users WHERE phone_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $phone_number);
        $stmt->execute();
        $result = $stmt->get_result();

        // Vérifier si l'utilisateur existe
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                // Authentification réussie, générer un token
                $token = bin2hex(random_bytes(16));

                // Mettre à jour le token dans la base de données
                $update_sql = "UPDATE users SET token = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $token, $user['id']);
                $update_stmt->execute();

                // Stocker le token dans la session
                $_SESSION['token'] = $token;

                
                header("Location: ../sms.php");
                exit();
            } else {
                // Mot de passe incorrect
                header("Location: login.php?error=Vérifiez vos identifiants ou créez un compte");
                exit();
            }
        } else {
            // Numéro de téléphone non trouvé
            header("Location: login.php?error=Vérifiez vos identifiants ou créez un compte");
            exit();
        }
    }

    // Fermer la connexion
    $conn->close();
