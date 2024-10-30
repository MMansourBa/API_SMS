<?php
// Configurations et URL de l'API
$access_token = 'eyJ0eXAiOiJKV1QiLCJ2ZXIiOiIxLjAiLCJhbGciOiJFUzM4NCIsImtpZCI6Ikg1RkdUNXhDUlJWU0NseG5vTXZCWEtUM1AyckhTRVZUNV9VdE16UFdCYTQifQ.eyJpc3MiOiJodHRwczovL2FwaS5vcmFuZ2UuY29tL29hdXRoL3YzIiwiYXVkIjpbIm9wZSJdLCJleHAiOjE3MzAyNDM5MzIsImlhdCI6MTczMDI0MDMzMiwianRpIjoibVhXRG1RQWZ1RlZoOTZreWNqTTV4emN3TDdUREsyMFVqejhTZDlub2M2S29UTTNMVXhSS3ZxQmQyVWpRSDRzempjc2JVMlRxWHFnTThneXZKQWNQWXUzV09ZTWl6eGNueFZQVSIsImNsaWVudF9pZCI6InU4a2VYSElQeEVHRkFTTjc4VXJ2Zk42VzNIOTRXckhNIiwic3ViIjoidThrZVhISVB4RUdGQVNONzhVcnZmTjZXM0g5NFdySE0iLCJjbGllbnRfbmFtZSI6eyJkZWZhdWx0IjoiU1lOQ1JPIn0sImNsaWVudF90YWciOiI0dnZKYWw1WE1pVG5nb3NrIiwic2NvcGUiOlsib3BlOnNtc19hZG1pbjp2MTphY2Nlc3MiLCJvcGU6c21zbWVzc2FnaW5nOnYxOmFjY2VzcyJdLCJtY28iOiJTRUtBUEkifQ.6DiQSHpNSOB28kJD8dh2zRjGtGeJ37W71C9tGBZgWUtNS6dnn3ow55CqJv3qOaz_WzXDzepHatXQYKXp99slkiD-HkiUKfUbujz_dnHEkQLvl-KVRHBVF9jve03qUGbY';

// Vérifiez si les données JSON sont reçues
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['sender_phone']) && isset($input['receiver_phone']) && isset($input['message'])) {
    $sender_phone_number = $input['sender_phone'];
    $receiver_phone_number = $input['receiver_phone'];
    $message = $input['message']; 
    $url = "https://api.orange.com/smsmessaging/v1/outbound/tel:$sender_phone_number/requests";

    $data = [
        "outboundSMSMessageRequest" => [
            "address" => "tel:$receiver_phone_number",
            "senderAddress" => "tel:$sender_phone_number",
            "outboundSMSTextMessage" => [
                "message" => $message
            ]
        ]
    ];

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Désactive la vérification SSL (pour test)

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Affichage des résultats
    if ($http_code === 201) {
        echo json_encode(["status" => "success", "message" => "SMS envoyé avec succès."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erreur lors de l'envoi du SMS. Code HTTP: $http_code", "details" => $response]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Numéro de téléphone ou message manquant."]);
}
