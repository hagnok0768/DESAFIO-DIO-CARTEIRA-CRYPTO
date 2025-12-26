<?php
// Dashboard: lista endereços e consulta saldo via API externa
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
$stmt = $pdo->query('SELECT * FROM wallets ORDER BY created_at DESC');
$wallets = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
        <meta charset="UTF-8">
        <title>Dashboard Bitcoin Wallets</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <style>
                .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
                .header .small{color:var(--muted)}
        </style>
</head>
<body>
        <div class="wrap">
            <div class="header">
                <div>
                    <div class="title">Block Explorer Simulado</div>
                    <div class="small">Endereços salvos no banco de dados</div>
                </div>
                <div>
                    <a class="btn" href="index.php">Gerador</a>
                </div>
            </div>

            <div class="card">
                <table class="table" id="wallets">
                    <thead><tr><th>Endereço</th><th>Saldo</th><th>Transações</th></tr></thead>
                    <tbody>
                    <?php foreach ($wallets as $w): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($w['address']) ?></code></td>
                            <td class="balance" data-address="<?= htmlspecialchars($w['address']) ?>">Carregando...</td>
                            <td class="small">--</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        // Usa proxy server-side para evitar CORS
        async function fetchBalance(addr){
            try{
                const res = await fetch('api/proxy_balance.php?address=' + encodeURIComponent(addr));
                if(!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                return data;
            }catch(e){
                return { error: true };
            }
        }

        document.querySelectorAll('.balance').forEach(async function(td){
            const addr = td.getAttribute('data-address');
            const data = await fetchBalance(addr);
            if(data && !data.error){
                td.textContent = (data.final_balance/1e8).toFixed(8) + ' BTC';
            } else {
                td.textContent = 'Erro';
            }
        });
        </script>
</body>
</html>
