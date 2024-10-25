<?php
    // Connexion à la base de données
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "ONYX";

    // Créer la connexion
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    $message = ''; // Variable pour stocker les messages d'erreur

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les valeurs du formulaire
        $username = $_POST['username'];
        $phone_number = $_POST['phone_number'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Vérifier si les mots de passe correspondent
        if ($password !== $confirm_password) {
            $message = "Les mots de passe ne correspondent pas.";
        } else {
            // Vérifier si le username ou le phone_number existe déjà
            $check_sql = "SELECT * FROM users WHERE username = ? OR phone_number = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("ss", $username, $phone_number);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Vérification pour savoir si c'est le username ou le phone_number qui existe déjà
                $row = $result->fetch_assoc();
                if ($row['username'] == $username) {
                    $message = "Username déjà utilisé.";
                } elseif ($row['phone_number'] == $phone_number) {
                    $message = "Phone number déjà utilisé.";
                }
            } else {
                // Si le username et le phone_number n'existent pas, on procède à l'insertion
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_sql = "INSERT INTO users (username, phone_number, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("sss", $username, $phone_number, $hashed_password);

                if ($stmt->execute()) {
                    // Rediriger vers la page login.php avec un message de succès si l'inscription réussit
                    header("Location: login.php?success=Inscription réussie, veuillez vous connecter.");
                    exit(); // S'assurer que la redirection est bien exécutée
                } else {
                    $message = "Erreur lors de l'inscription : " . $stmt->error;
                }
            }
        }
    }

    // Fermer la connexion
    $conn->close();

    // Si le message d'erreur n'est pas vide, le rediriger avec le message
    if (!empty($message)) {
        header("Location: register.php?error=" . urlencode($message));
        exit();
    }

    // Débogage : vérifiez le message
    var_dump($message);
?>
