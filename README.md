# DESAFIO-DIO-CARTEIRA-CRYPTO
DESAFIO DIO CARTEIRA CRYPTO

🪙 Bitcoin Wallet Generator & Explorer (PHP/JS/MySQL)
📌 Visão Geral
Este repositório contém um sistema de geração de carteiras de Bitcoin e monitorização de transações. O projeto foca-se numa arquitetura híbrida, utilizando JavaScript para operações criptográficas no cliente e PHP para a lógica de servidor e persistência de dados.

O objetivo principal é permitir a criação de uma carteira compatível com o Electrum (padrão BIP39) e acompanhar o histórico de endereços através de uma interface web.

🛠️ Tecnologias Utilizadas
Frontend: HTML5, CSS3, JavaScript (ES6+).

Criptografia: bitcoinjs-lib e bip39 (via Browserify/CDN).

Backend: PHP 8.x.

Base de Dados: MySQL.

Integração: Padrões BIP32, BIP39 e BIP44/84.

🚀 Funcionalidades Planeadas
Gerador HD: Criação de Seed Phrases (12 palavras) de forma segura no navegador.

Compatibilidade: Importação total no software Electrum.

Dashboard: Visualização de saldo e transações (Block Explorer).

Gestão: Armazenamento de chaves públicas e histórico de transações no MySQL.

📁 Estrutura de Pastas Sugerida
Plaintext

├── assets/             # CSS, Imagens e Bibliotecas JS
├── db/                 # Scripts SQL para criação do banco de dados
├── includes/           # Scripts PHP (Conexão DB, Funções)
├── api/                # Endpoints PHP para comunicação com o front
├── index.php           # Página principal do Gerador
└── README.md           # Documentação
