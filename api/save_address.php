<?php
// Endpoint para salvar endereço público no banco
require_once '../includes/db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $address = $data['address'] ?? '';
    if ($address) {
        $stmt = $pdo->prepare('INSERT INTO wallets (address) VALUES (?)');
        $stmt->execute([$address]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Endereço ausente']);
    }
}
