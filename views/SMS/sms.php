<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['token'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: Login/login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS</title>
    <link rel="stylesheet" href="styles.css">

    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="box">
            <h4>Envoyer un message</h4>

            <div class="phone">
                <i class="fas fa-phone"></i>
                <input type="text" placeholder="Numéro de téléphone de l'expéditeur" id="sender-phone">
            </div>

            <div class="phone">
                <i class="fas fa-phone"></i>
                <input type="text" placeholder="Numéro de téléphone" id="phone">
            </div>
            
            <div class="message-box">
                <textarea id="msg" cols="10" rows="8" placeholder="Ecrire un message..."></textarea>
            </div>
            <div class="button">
                <button id="send" onclick="message()">Envoyer</button>
            </div>
            <div class="message">
                <div class="success" id="success">Votre message a été envoyé avec succès !</div>
                <div class="danger" id="danger">Les champs ne peuvent pas être vides !</div>
            </div>
        </div>
    </div>


    <script src="script.js"></script>
</body>
</html>