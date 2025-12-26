-- Estrutura do MySQL para carteiras e transações
CREATE TABLE wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wallet_id INT,
    txid VARCHAR(100),
    amount DECIMAL(18,8),
    timestamp TIMESTAMP,
    FOREIGN KEY (wallet_id) REFERENCES wallets(id)
);
