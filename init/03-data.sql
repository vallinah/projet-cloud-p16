 insert into admin values('ADM001','admin','antsamadagascar@gmail.com','admin');
 
 INSERT INTO session_config (minute_timeout)
 VALUES (5);

INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Bitcoin', 'BTC', 43500);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Ethereum', 'ETH', 3000);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Binance Coin', 'BNB', 380);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Cardano', 'ADA', 1.35);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Solana', 'SOL', 150);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Ripple', 'XRP', 0.75);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Polkadot', 'DOT', 20);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Dogecoin', 'DOGE', 0.25);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Avalanche', 'AVAX', 75);
INSERT INTO cryptocurrencies (name, symbol, current_price) VALUES('Chainlink', 'LINK', 28);

INSERT INTO commission(valeur,date_commission) values(2,NOW());

INSERT INTO type_analyse (type_analyse)  
VALUES  
    ('somme'),  
    ('moyenne');
    
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Ratovonandrasana', 'Aina Ny Antsa', 'antsamadagascar@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '2005-07-29', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Otisoa', 'Vallinah', 'otisoavallinah@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '2004-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Rakotojaona', 'Fyh', 'fyrakotojaona@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '2003-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Rakotomalala', 'Kiady', 'krakotomalala0@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '2003-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Rabe', 'Ivo', 'ivomihary@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '1990-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Randry', 'Zo', 'atn.randr@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '1990-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Safidy', 'aina', 'safidynyaina@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '1990-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('jean', 'dupont', 'dupont@gmail.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '1998-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Ramihajanirina', 'Aina', 'sariaka@example.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '1990-05-15', NOW(), TRUE);
 INSERT INTO users (first_name, last_name, email, password, date_of_birth, created_date, is_valid) VALUES
 ('Randria', 'Jeddy', 'jeddy@example.com', 'jZae727K08KaOmKSgOaGzww/XVqGr/PKEgIMkjrcbJI=', '1990-05-15', NOW(), TRUE);

INSERT INTO crypto_wallets (user_id, crypto_id, amount)
VALUES 
 ('USE-00001', 'CRY-00001', 2.5),
 ('USE-00001', 'CRY-00002', 5),
 ('USE-00002', 'CRY-00001', 3.7),
 ('USE-00002', 'CRY-00002', 7),
 ('USE-00003', 'CRY-00001', 1.5),
 ('USE-00003', 'CRY-00002', 6),
 ('USE-00004', 'CRY-00001', 2),
 ('USE-00004', 'CRY-00002', 8),
 ('USE-00005', 'CRY-00001', 4.3),
 ('USE-00005', 'CRY-00002', 3.5),
 ('USE-00006', 'CRY-00001', 5),
 ('USE-00006', 'CRY-00002', 2.5),
 ('USE-00007', 'CRY-00001', 3.2),
 ('USE-00007', 'CRY-00002', 4.5),
 ('USE-00008', 'CRY-00001', 0.7),
 ('USE-00008', 'CRY-00002', 9),
 ('USE-00009', 'CRY-00001', 1.8),
 ('USE-00009', 'CRY-00002', 3.6),
 ('USE-00010', 'CRY-00001', 2.4),
 ('USE-00010', 'CRY-00002', 1.9);


 INSERT INTO mouvement_fond (montant_depot, date_mouvement_fond, montant_retrait, user_id, is_valid)
 VALUES
 (1000000, '2024-02-05 10:15:00', NULL, 'USE-00002',TRUE),
 (NULL, '2024-02-05 12:30:00', 450, 'USE-00002',TRUE),
 (NULL, '2024-02-05 12:30:00', 735, 'USE-00002',TRUE),
 (1000000, '2024-02-05 10:15:00', NULL, 'USE-00001',TRUE),
 (NULL, '2024-02-05 12:30:00', 585, 'USE-00001',TRUE),
 (NULL, '2024-02-05 12:30:00', 490, 'USE-00001',TRUE);


INSERT INTO mouvement_fond (montant_depot, date_mouvement_fond, montant_retrait, user_id, is_valid)
VALUES
 (1500000, '2025-02-07 09:00:00', NULL, 'USE-00003', TRUE),
 (NULL, '2025-02-07 11:00:00', 300, 'USE-00003', TRUE),
 (2000000, '2025-02-07 14:30:00', NULL, 'USE-00004', False),
 (NULL, '2025-02-07 16:45:00', 400, 'USE-00004', TRUE),
 (1200000, '2025-02-07 18:15:00', NULL, 'USE-00005', False),
 (NULL, '2025-02-07 20:00:00', 250, 'USE-00005', TRUE),
 (3000000, '2025-02-07 08:00:00', NULL, 'USE-00006', TRUE),
 (NULL, '2025-02-07 10:30:00', 1500, 'USE-00006', TRUE),
 (1700000, '2025-02-07 12:30:00', NULL, 'USE-00007', TRUE),
 (NULL, '2025-02-07 13:45:00', 600, 'USE-00007', TRUE);

 INSERT INTO mouvement_crypto (nombre, cours, vente, achat, crypto_id, user_id, date_mouvement, is_valid)
 VALUES
   (0.5, 300, 1, 0, 'CRY-00001', 'USE-00001', '2025-02-06 10:00:00', FALSE), 
   (2, 245, 0, 1, 'CRY-00002', 'USE-00001', '2025-02-04 11:30:00', TRUE),
   (1.3, 200, 0, 1, 'CRY-00001', 'USE-00001', '2025-02-04 11:30:00', TRUE),
   (1, 450, 0, 1, 'CRY-00001', 'USE-00002', '2025-02-06 12:45:00', TRUE),
   (3, 735, 0, 1, 'CRY-00002', 'USE-00002', '2025-02-06 14:00:00', TRUE),  
   (2, 300, 0, 1, 'CRY-00002', 'USE-00002', '2025-02-06 15:15:00', FALSE); 


INSERT INTO mouvement_crypto (nombre, cours, vente, achat, crypto_id, user_id, date_mouvement, is_valid)
VALUES
   (0.2, 300, 1, 0, 'CRY-00001', 'USE-00003', '2025-02-07 09:30:00', TRUE), 
   (3, 245, 0, 1, 'CRY-00002', 'USE-00003', '2025-02-07 10:00:00', TRUE),
   (1.5, 200, 0, 1, 'CRY-00001', 'USE-00004', '2025-02-07 14:45:00', TRUE),
   (2, 450, 0, 1, 'CRY-00001', 'USE-00004', '2025-02-07 15:00:00', TRUE),
   (2, 735, 0, 1, 'CRY-00002', 'USE-00005', '2025-02-07 18:30:00', FALSE), 
   (0.5, 300, 1, 0, 'CRY-00002', 'USE-00006', '2025-02-07 19:00:00', FALSE),
   (1, 245, 0, 1, 'CRY-00002', 'USE-00006', '2025-02-07 20:30:00', TRUE),
   (0.5, 200, 0, 1, 'CRY-00001', 'USE-00007', '2025-02-07 12:00:00', TRUE),
   (1, 450, 0, 1, 'CRY-00001', 'USE-00007', '2025-02-07 13:00:00', TRUE),
   (3, 300, 0, 1, 'CRY-00002', 'USE-00007', '2025-02-07 13:45:00', FALSE);




CREATE VIEW solde_utilisateur AS
SELECT 
    user_id,
    SUM(CASE WHEN montant_depot IS NOT NULL THEN montant_depot ELSE 0 END) - 
    SUM(CASE WHEN montant_retrait IS NOT NULL THEN montant_retrait ELSE 0 END) AS solde
FROM 
    mouvement_fond
WHERE 
    is_valid = TRUE
GROUP BY 
    user_id;
