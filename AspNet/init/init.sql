-- Générateur d'ID formaté (générique)
CREATE OR REPLACE FUNCTION generate_id(prefix TEXT, seq_name TEXT) 
RETURNS TEXT AS $$
DECLARE
    seq_value INT;
BEGIN
    EXECUTE format('SELECT nextval(%L)', seq_name) INTO seq_value;

    RETURN prefix || LPAD(seq_value::TEXT, 5, '0');  
END;
$$ LANGUAGE plpgsql;

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


insert into admin values('ADM001','admin','otisoavallinah@gmail.com','admin');
insert into users(first_name,last_name,email,password,date_of_birth,created_date,is_valid) values('otisoa','vallinah','mandafilamantra@gamil.com','vallinah','2001-12-19','2024-12-19',true)