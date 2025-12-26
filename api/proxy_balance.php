<?php
// proxy_balance.php
// Faz requisição ao BlockCypher (ou outra API pública) e retorna JSON simplificado
// Uso: GET ?address=bc1...
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db_connect.php';

$addr = $_GET['address'] ?? '';
if (!$addr) {
    http_response_code(400);
    echo json_encode(['error' => 'address missing']);
    exit;
}

$api = 'https://api.blockcypher.com/v1/btc/main/addrs/' . urlencode($addr);
$ch = curl_init($api);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($res === false) {
    http_response_code(502);
    echo json_encode(['error' => 'failed to fetch']);
    exit;
}
curl_close($ch);
$data = json_decode($res, true);
if (!$data) {
    http_response_code(502);
    echo json_encode(['error' => 'invalid response from upstream']);
    exit;
}

// Retornar apenas campos relevantes
echo json_encode([
    'address' => $addr,
    'final_balance' => $data['final_balance'] ?? 0,
    'n_tx' => $data['n_tx'] ?? 0,
    'unconfirmed_balance' => $data['unconfirmed_balance'] ?? 0,
]);
