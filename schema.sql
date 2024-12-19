CREATE TABLE admin(
   admin_id VARCHAR(50) ,
   login VARCHAR(50)  NOT NULL,
   email VARCHAR(250)  NOT NULL,
   password VARCHAR(250)  NOT NULL,
   PRIMARY KEY(admin_id),
   UNIQUE(login),
   UNIQUE(email)
);

CREATE TABLE users(
   user_id VARCHAR(50) ,
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
   user_id VARCHAR(50)  NOT NULL,
   PRIMARY KEY(pin_id),
   FOREIGN KEY(user_id) REFERENCES users(user_id)
);

CREATE TABLE token(
   id_token SERIAL,
   token VARCHAR(250)  NOT NULL,
   created_date TIMESTAMP NOT NULL,
   user_id VARCHAR(50)  NOT NULL,
   PRIMARY KEY(id_token),
   UNIQUE(user_id),
   FOREIGN KEY(user_id) REFERENCES users(user_id)
);
