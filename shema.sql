CREATE DATABASE doingsdone_db
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone_db;

CREATE TABLE project (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128)
);

CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_id INT NOT NULL,
  date_create TIMESTAMP,
  status BOOLEAN DEFAULT 0,
  name VARCHAR(128),
  url VARCHAR(128),
  due_date TIMESTAMP,
  FOREIGN KEY (project_id)  REFERENCES project (id)

);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_registration TIMESTAMP,
  email VARCHAR(128),
  name VARCHAR(128),
  password_hash CHAR(64)
);
