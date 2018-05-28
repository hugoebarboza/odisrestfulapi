CREATE DATABASE IF NOT EXISTS odisrestfulapi;
USE odisrestfulapi;

CREATE TABLE users(
id int(255) auto_increment not null,
role varchar(20), 
name varchar(255),
surname varchar(255),
email varchar(255),
password varchar(255),
status int(2),
create_at timestamp,
update_at timestamp,
remember_token varchar(255),
CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;


CREATE TABLE clientes(
id int(255) auto_increment not null,
role varchar(20),
name varchar(255),
surname varchar(255),
email varchar(255),
status int(2),
create_at timestamp,
update_at timestamp,
CONSTRAINT pk_clientes PRIMARY KEY(id)
)ENGINE=InnoDb;

CREATE TABLE marcas(
id int(255) auto_increment not null,
user_id int(255) not null,
cliente_id int(255) not null,
title varchar(255),
description text,
website varchar(255),
status int(2),
create_at DATETIME DEFAULT NULL,
update_at DATETIME DEFAULT NULL,
CONSTRAINT pk_marcas PRIMARY KEY(id),
CONSTRAINT fk_marcas_users FOREIGN KEY(user_id) REFERENCES users(id),
CONSTRAINT fk_marcas_clientes FOREIGN KEY(cliente_id) REFERENCES clientes(id)
)ENGINE=InnoDb;

