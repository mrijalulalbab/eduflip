<?php
require_once '../../../includes/config.php';
require_once '../../../includes/auth.php';

header('Content-Type: application/json');

// Auth Check
if (!isLoggedIn() || $_SESSION['role'] !== 'mahasiswa') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get User & API Key
// Get User
$user = getUserById($_SESSION['user_id']);

// ------------------------------------------------------------------
// HARDCODED API KEY (Paste your key below)
// ------------------------------------------------------------------
$apiKey = "YOUR_GEMINI_API_KEY_HERE"; // Replace with your API key from https://aistudio.google.com/app/apikey
// ------------------------------------------------------------------


if (empty($apiKey)) {
    echo json_encode(['success' => false, 'message' => 'API Key is missing']);
    exit;
}

// Get Input
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(['success' => false, 'message' => 'Message is empty']);
    exit;
}

// Gemini API URL
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro-latest:generateContent?key=" . $apiKey;

// Prepare Data
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => $userMessage]
            ]
        ]
    ]
];

// Call API using cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for local Docker SSL issues

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(['success' => false, 'message' => 'Connection error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Parse Response
$result = json_decode($response, true);

if ($httpCode !== 200) {
    echo json_encode([
        'success' => false, 
        'message' => 'API Error: ' . ($result['error']['message'] ?? 'Unknown error')
    ]);
    exit;
}

// Extract Text
$aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? "I'm sorry, I couldn't understand that.";

echo json_encode([
    'success' => true,
    'reply' => $aiText
]);
