<?php
function predictSentiment($text) {
    $url = "http://127.0.0.1:5000/predict";
    $data = json_encode(["text" => $text]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $result = json_decode($response, true);
        return $result['sentiment'] ?? 'unknown';
    } else {
        return 'error';
    }
}
?>
