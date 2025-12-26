<?php
// Endpoint para consultar histórico de endereços e transações
require_once '../includes/db_connect.php';
$stmt = $pdo->query('SELECT * FROM wallets ORDER BY created_at DESC');
$wallets = $stmt->fetchAll();
echo json_encode($wallets);
