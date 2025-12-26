<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Carteira Bitcoin HD</title>
    <link rel="stylesheet" href="assets/css/style.css">
        <!-- Load local libs first (assets/libs/) then fallback to CDN if missing -->
        <script>
        (function(){
            const libs = [
                { local: 'assets/libs/bip39.browser.js', cdn: 'https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js' },
                { local: 'assets/libs/bitcoinjs-lib.min.js', cdn: 'https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js' }
            ];
            function load(src){
                return new Promise((res, rej)=>{
                    const s = document.createElement('script');
                    s.src = src; s.async = true;
                    s.onload = () => res(src);
                    s.onerror = () => rej(new Error('failed to load ' + src));
                    document.head.appendChild(s);
                });
            }
            (async function(){
                for(const lib of libs){
                    // try local first
                    try{ await load(lib.local); continue; }catch(e){
                        // local failed — try CDN
                        try{ await load(lib.cdn); continue; }catch(e2){ console.error('Failed to load lib', lib, e2); }
                    }
                }
            })();
        })();
        </script>
        <!-- Additional CDN fallbacks recommended for shared hosting (Hostinger) -->
        <script src="https://cdn.jsdelivr.net/npm/bip39@3.0.1/src/index.min.js"></script>
        <script src="https://bundle.run/bitcoinjs-lib@5.2.0"></script>
        <script src="https://bundle.run/buffer@6.0.3"></script>
        <script src="assets/js/wallet.js" defer></script>
</head>
</body>
<body>
    <div class="wrap">
        <header class="header-bar">
            <div class="logo">
                <div class="mark">B</div>
                <div class="text">BTC Wallet</div>
            </div>
            <nav>
                <a class="btn ghost" href="dashboard.php">Dashboard</a>
            </nav>
        </header>

        <section class="hero">
            <div class="hero-left">
                <div class="panel">
                    <div class="title">Gerador de Carteira Bitcoin HD</div>
                    <div class="muted">Gere uma seed no seu navegador. Nunca envie a seed ou a chave privada ao servidor.</div>

                    <div style="margin-top:18px" class="kpi">
                        <div class="item">
                            <div class="small">Padrão</div>
                            <div class="code">BIP84 (m/84'/0'/0'/0/0)</div>
                        </div>
                        <div class="item">
                            <div class="small">SegWit</div>
                            <div class="code">Bech32 (bc1)</div>
                        </div>
                    </div>

                    <div style="margin-top:16px">
                        <button class="btn primary" id="btn-generate">Gerar carteira</button>
                        <button class="btn ghost" id="btn-new">Gerar somente endereço</button>
                    </div>

                    <div class="footnote">Ao gerar, o endereço será salvo no servidor — a seed e a WIF permanecerão localmente.</div>
                </div>

                <div style="margin-top:16px" class="panel">
                    <div class="small">Seed (guarde com segurança)</div>
                    <div class="output" id="out-mnemonic">—</div>
                    <div class="small" style="margin-top:8px">Chave Privada (WIF)</div>
                    <div class="output" id="out-wif">—</div>
                </div>
            </div>

            <div class="hero-right">
                <div class="panel">
                    <div class="small">Endereço</div>
                    <div class="output" id="out-address"><code>—</code></div>
                    <img id="qr-img" class="qr" src="" alt="QR" style="display:none" />
                    <div style="display:flex;gap:8px;margin-top:8px">
                        <button class="btn" id="copy-address">Copiar</button>
                        <button class="btn ghost" id="show-qr">Mostrar QR</button>
                    </div>
                </div>
            </div>
        </section>
                <div style="max-width:1100px;margin:12px auto 60px;">
                    <div id="save-result" class="footnote"></div>
                </div>
    </div>

    <script>
    const outMnemonic = document.getElementById('out-mnemonic');
    const outWif = document.getElementById('out-wif');
    const outAddress = document.getElementById('out-address');
    const qrImg = document.getElementById('qr-img');
    const btnGenerate = document.getElementById('btn-generate');
    const btnNew = document.getElementById('btn-new');
    const saveResult = document.getElementById('save-result') || document.createElement('div');

    function setBusy(state){
        btnGenerate.disabled = state;
        btnNew.disabled = state;
        btnGenerate.style.opacity = state ? '0.6' : '1';
    }

    async function saveAddressToServer(address){
        try{
            // send as application/x-www-form-urlencoded for better compatibility with simple PHP hosts
            const body = new URLSearchParams();
            body.append('address', address);
            const r = await fetch('api/save_address.php', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: body.toString() });
            try{ return r.ok ? await r.json() : { success:false }; }catch(e){ return { success:false } }
        }catch(e){ return { success:false } }
    }

    async function populate(result, showSecrets=true){
        if(showSecrets){ outMnemonic.innerHTML = '<code>' + result.mnemonic + '</code>'; outWif.innerHTML = '<code>' + result.wif + '</code>'; }
        else { outMnemonic.innerHTML = '<span class="muted">(não exibido)</span>'; outWif.innerHTML = '<span class="muted">(não exibido)</span>'; }
        outAddress.innerHTML = '<code>' + result.address + '</code>';
        qrImg.src = 'https://chart.googleapis.com/chart?chs=240x240&cht=qr&chl=' + encodeURIComponent(result.address);
        qrImg.style.display = 'none';
        const saved = await saveAddressToServer(result.address);
        saveResult.textContent = saved && saved.success ? 'Endereço salvo no servidor.' : 'Falha ao salvar endereço.';
    }

    async function handleGenerate(showSecrets){
        setBusy(true);
        saveResult.textContent = '';
        try{
            if(typeof window.generateWallet !== 'function') throw new Error('Função generateWallet não disponível. Verifique se os scripts foram carregados.');
            const res = await window.generateWallet();
            await populate(res, showSecrets);
        }catch(err){
            console.error(err);
            saveResult.textContent = 'Erro: ' + (err.message || err);
        }finally{ setBusy(false); }
    }

    btnGenerate.addEventListener('click', function(){ handleGenerate(true); });
    btnNew.addEventListener('click', function(){ handleGenerate(false); });

    document.getElementById('copy-address').addEventListener('click', function(){
        const txt = outAddress.textContent.trim();
        navigator.clipboard.writeText(txt).then(()=>{ alert('Endereço copiado') }).catch(()=>{ alert('Falha ao copiar') });
    });

    document.getElementById('show-qr').addEventListener('click', function(){
        if(qrImg.style.display === 'none') qrImg.style.display = 'block'; else qrImg.style.display = 'none';
    });
    </script>
</body>
</html>
