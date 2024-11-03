function message() {
    const senderPhone = document.getElementById('sender-phone').value;
    const receiverPhone = document.getElementById('phone').value;
    const message = document.getElementById('msg').value;

    const successMessage = document.getElementById('success');
    const errorMessage = document.getElementById('danger');

    // Cacher les messages au départ
    successMessage.style.display = 'none';
    errorMessage.style.display = 'none';

    // Vérification des champs vides
    if (!senderPhone || !receiverPhone || !message) {
        errorMessage.style.display = 'block'; // Affiche le message d'erreur si un champ est vide
        return;
    }

    // Si les champs sont remplis, envoi du message
    const data = {
        sender_phone: senderPhone,
        receiver_phone: receiverPhone,
        message: message
    };

    fetch('send_sms.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            successMessage.style.display = 'block'; // Affiche le message de succès
        } else {
            errorMessage.style.display = 'block'; // Affiche un message d'erreur si l'envoi échoue
            errorMessage.innerText = "Erreur lors de l'envoi du SMS.";
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        errorMessage.style.display = 'block'; 
        errorMessage.innerText = "Erreur lors de l'envoi du SMS.";
    });
}
