🪙 Project Blueprint: Bitcoin Wallet System (PHP/JS/MySQL)
📋 Contexto e Escopo
Este projeto é um Gerador de Carteiras Bitcoin Hierárquico Determinístico (HD). A geração da Seed Phrase ocorre no navegador (JavaScript) para garantir que a chave privada nunca toque o servidor. O PHP atua como o backend para gerenciar os endereços públicos e o histórico de transações.

🛠️ Stack Tecnológica
Frontend: HTML5, CSS3 (Tailwind ou Bootstrap para o Dashboard).

Criptografia Client-side: bitcoinjs-lib e bip39 via CDN.

Backend: PHP 8.x (POO ou Procedural estruturado).

Banco de Dados: MySQL.

Padrões Bitcoin: BIP32, BIP39, BIP44/84 (SegWit Native).

🗂️ Estrutura de Arquivos solicitada
Plaintext

├── assets/
│   ├── css/style.css       # Estilização do gerador e dashboard
│   └── js/wallet.js        # Lógica de criptografia e geração BIP39
├── db/
│   └── schema.sql          # Estrutura do MySQL (Wallets e Transactions)
├── includes/
│   ├── db_connect.php      # Conexão PDO com o MySQL
│   └── functions.php       # Helpers PHP (formatação de saldo, etc)
├── api/
│   ├── save_address.php    # Endpoint para receber endereço via Fetch/AJAX
│   └── get_history.php     # Endpoint para consultar histórico no banco
├── index.php               # Interface do Gerador (Seed Phrase)
└── dashboard.php           # Visualização do Block Explorer (Saldos)
🎯 Regras de Implementação
Segurança: A Seed Phrase e a Private Key devem aparecer apenas no index.php após a geração e nunca ser enviadas via formulário para o PHP.

Integração Electrum: A derivação deve obrigatoriamente usar o caminho m/84'/0'/0'/0/0 para que o endereço bc1q... seja idêntico no software Electrum.

Fluxo: - O JS gera a carteira.

O JS dispara um fetch() para api/save_address.php enviando apenas o endereço público.

O PHP salva no MySQL.

Visualização: O dashboard.php deve listar os endereços salvos e buscar dados de uma API externa (como BlockCypher) para simular o Explorer.