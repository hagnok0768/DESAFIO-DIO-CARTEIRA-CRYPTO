<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Carteira Bitcoin HD</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js"></script>
    <script src="https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js"></script>
    <script src="assets/js/wallet.js" defer></script>
</head>
</body>
<body>
    <div class="wrap">
        <div class="grid">
            <div>
                <div class="card">
                    <div class="title">Gerador de Carteira Bitcoin HD</div>
                    <div class="small">A seed e a chave privada são geradas apenas no navegador — nunca enviadas ao servidor.</div>
                    <div style="margin-top:16px">
                        <button class="btn" id="btn-generate">Gerar carteira</button>
                        <button class="btn" id="btn-new" style="margin-left:8px;background:#f97316;color:#06202a">Gerar somente endereço</button>
                    </div>
                    <div class="output" id="out-mnemonic">Seed: —</div>
                    <div class="output" id="out-wif">Chave Privada: —</div>
                    <div class="output" id="out-address">Endereço: —</div>
                    <div class="small" id="save-result"></div>
                </div>
            </div>

            <aside>
                <div class="card">
                    <div class="title">Ações</div>
                    <div class="small">Copie seu endereço e verifique no dashboard.</div>
                    <div style="margin-top:12px">
                        <button class="btn" id="copy-address">Copiar endereço</button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
    // Elementos
    const outMnemonic = document.getElementById('out-mnemonic');
    const outWif = document.getElementById('out-wif');
    const outAddress = document.getElementById('out-address');
    const saveResult = document.getElementById('save-result');

    async function onWalletGenerated(res){
        outMnemonic.innerHTML = '<strong>Seed:</strong><br>' + res.mnemonic;
        outWif.innerHTML = '<strong>Chave Privada (WIF):</strong><br>' + res.wif;
        outAddress.innerHTML = '<strong>Endereço:</strong><br><code>' + res.address + '</code>';
        try{
            const r = await fetch('api/save_address.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ address: res.address }) });
            const j = await r.json();
            if(j && j.success) saveResult.textContent = 'Endereço salvo no servidor.'; else saveResult.textContent = 'Erro ao salvar.';
        }catch(e){ saveResult.textContent = 'Erro de conexão ao salvar.' }
    }

    document.getElementById('btn-generate').addEventListener('click', async function(){
        const res = await window.generateWallet();
        onWalletGenerated(res);
    });

    document.getElementById('btn-new').addEventListener('click', async function(){
        const res = await window.generateWallet();
        outMnemonic.innerHTML = '<span class="small">(não exibido)</span>';
        outWif.innerHTML = '<span class="small">(não exibido)</span>';
        outAddress.innerHTML = '<strong>Endereço:</strong><br><code>' + res.address + '</code>';
        try{ await fetch('api/save_address.php', {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({address:res.address})}); saveResult.textContent='Endereço salvo.' }catch(e){ saveResult.textContent='Erro ao salvar.' }
    });

    document.getElementById('copy-address').addEventListener('click', function(){
        const txt = outAddress.textContent || '';
        navigator.clipboard.writeText(txt).then(()=>{ alert('Endereço copiado') }).catch(()=>{ alert('Falha ao copiar') });
    });
    </script>
</body>
</html>
