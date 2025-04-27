CREATE  EXTENSION IF NOT EXISTS pgcrypto;

CREATE SEQUENCE admin_id_seq START 1;
CREATE TABLE admin(
   admin_id VARCHAR(50) DEFAULT generate_id('ADM-', 'admin_id_seq'),
   login VARCHAR(50)  NOT NULL,
   email VARCHAR(250)  NOT NULL,
   password VARCHAR(250)  NOT NULL,
   PRIMARY KEY(admin_id),
   UNIQUE(login),
   UNIQUE(email)
);
 
CREATE SEQUENCE users_id_seq START 1;
CREATE TABLE users(
   user_id VARCHAR(50) DEFAULT generate_id('USE-', 'users_id_seq'),
   first_name VARCHAR(250)  NOT NULL,
   last_name VARCHAR(50)  NOT NULL,
   email VARCHAR(250)  NOT NULL,
   password VARCHAR(250)  NOT NULL,
   date_of_birth DATE NOT NULL,
   created_date TIMESTAMP NOT NULL,
   is_valid BOOLEAN NOT NULL,
   PRIMARY KEY(user_id)
);

CREATE TABLE email_pins(
   pin_id SERIAL,
   code_pin VARCHAR(50)  NOT NULL,
   created_date TIMESTAMP NOT NULL,
   expiration_date TIMESTAMP NOT NULL,
   user_id VARCHAR(50) NOT NULL,
   PRIMARY KEY(pin_id),
   FOREIGN KEY(user_id) REFERENCES users(user_id)
);

CREATE TABLE token(
   token_id SERIAL,
   token VARCHAR(250) NOT NULL,
   created_date TIMESTAMP NOT NULL,
   user_id VARCHAR(50)  NOT NULL,
   PRIMARY KEY(token_id),
   UNIQUE(user_id),
   FOREIGN KEY(user_id) REFERENCES users(user_id)
);

CREATE TABLE user_login (
   user_login_id SERIAL PRIMARY KEY,
   user_id VARCHAR(50) NOT NULL,  
   tentative INT NOT NULL DEFAULT 0,
   email VARCHAR(250),
   FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE session_config (
   session_config_id SERIAL PRIMARY KEY,
   minute_timeout DECIMAL(5,1) NOT NULL,
   tentative_max INT NOT NULL DEFAULT 3
);

CREATE TABLE session_users (
   session_users_id SERIAL PRIMARY KEY,
   created_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   name VARCHAR(150) NOT NULL,  
   value VARCHAR(250) NOT NULL,
   user_id VARCHAR(50) NOT NULL, 
   FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE SEQUENCE crypto_id_seq START 1;
CREATE TABLE cryptocurrencies (
    crypto_id VARCHAR(50) DEFAULT generate_id('CRY-', 'crypto_id_seq'),
    name VARCHAR(100) NOT NULL,
    symbol VARCHAR(10) NOT NULL,
    current_price DECIMAL(20,8) NOT NULL,
    created_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(crypto_id),
    UNIQUE(symbol)
);


CREATE SEQUENCE wallet_id_seq START 1;
CREATE TABLE crypto_wallets (
    wallet_id VARCHAR(50) DEFAULT generate_id('CRY-', 'wallet_id_seq'),
    user_id varchar REFERENCES users(user_id),
    crypto_id VARCHAR(50) REFERENCES cryptocurrencies(crypto_id),
    amount DECIMAL(20,8) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (crypto_id) REFERENCES cryptocurrencies(crypto_id)
);


CREATE TABLE mouvement_fond(
   id_mouvement_fond SERIAL,
   montant_retrait NUMERIC(18,2)  ,
   date_mouvement_fond TIMESTAMP,
   montant_depot NUMERIC(18,2)  ,
   user_id VARCHAR(50)  NOT NULL,
   is_valid BOOLEAN NOT NULL,
   PRIMARY KEY(id_mouvement_fond),
   FOREIGN KEY(user_id) REFERENCES users(user_id)
);
CREATE TABLE commission(
   id_commission SERIAL,
   valeur NUMERIC(15,7)  DEFAULT 0,
   date_commission TIMESTAMP ,
   PRIMARY KEY(id_commission)
);

CREATE TABLE mouvement_crypto(
   id_mouvement_crypto SERIAL,
   nombre NUMERIC(15,7)  ,
   cours NUMERIC(15,2)  ,
   vente INTEGER,
   achat INTEGER,
   crypto_id varchar,
   user_id VARCHAR(50)  NOT NULL,
   date_mouvement TIMESTAMP,
   is_valid BOOLEAN NOT NULL,
   id_commission INT,
   PRIMARY KEY(id_mouvement_crypto),
   FOREIGN KEY(crypto_id) REFERENCES cryptocurrencies(crypto_id),
   FOREIGN KEY(user_id) REFERENCES users(user_id),
   FOREIGN KEY(id_commission) REFERENCES commission(id_commission)
);

CREATE TABLE type_analyse(
   id_type SERIAL,
   type_analyse VARCHAR(150),
   PRIMARY KEY(id_type)
);



----historique 

CREATE TABLE crypto_price_history (
    history_id SERIAL PRIMARY KEY,
    crypto_id VARCHAR(50) NOT NULL,
    old_price DECIMAL(20,8) NOT NULL,
    new_price DECIMAL(20,8) NOT NULL,
    change_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (crypto_id) REFERENCES cryptocurrencies(crypto_id)
);

CREATE OR REPLACE FUNCTION log_crypto_price_changes() 
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.current_price IS DISTINCT FROM NEW.current_price THEN
        INSERT INTO crypto_price_history (crypto_id, old_price, new_price, change_date)
        VALUES (OLD.crypto_id, OLD.current_price, NEW.current_price, NOW());
        DELETE FROM crypto_price_history 
        WHERE history_id = (
            SELECT history_id 
            FROM crypto_price_history 
            WHERE crypto_id = OLD.crypto_id 
            ORDER BY change_date ASC 
            LIMIT 1
        ) 
        AND (SELECT COUNT(*) FROM crypto_price_history WHERE crypto_id = OLD.crypto_id) > 10;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_crypto_price_changes
BEFORE UPDATE ON cryptocurrencies
FOR EACH ROW
EXECUTE FUNCTION log_crypto_price_changes();
