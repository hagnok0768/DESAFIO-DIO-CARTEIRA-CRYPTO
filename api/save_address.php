<?php
// Endpoint para salvar endereço público no banco
require_once '../includes/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Accept JSON or form-urlencoded
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    $address = '';
    if (stripos($contentType, 'application/json') !== false) {
        $data = json_decode(file_get_contents('php://input'), true);
        $address = $data['address'] ?? '';
    } else {
        // form POST or x-www-form-urlencoded
        $address = $_POST['address'] ?? '';
        if (!$address) {
            // try raw parsing
            parse_str(file_get_contents('php://input'), $raw);
            $address = $raw['address'] ?? '';
        }
    }

    if ($address) {
        $stmt = $pdo->prepare('INSERT INTO wallets (address) VALUES (?)');
        $stmt->execute([$address]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Endereço ausente']);
    }
}
