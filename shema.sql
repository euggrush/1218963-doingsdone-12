CREATE DATABASE doingsdone_db
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone_db;

CREATE TABLE project (
  id INT AUTO_INCREMENT PRIMARY KEY,
  project_name VARCHAR(128)
);

CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_status BOOLEAN DEFAULT 0,
  task_name VARCHAR(128),
  task_url VARCHAR(128),
  task_due_dt TIMESTAMP
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128),
  usr_name VARCHAR(128),
  user_password CHAR(64)
);



-- 5.1. Проект
-- Состоит только из названия. Каждая задача может быть привязана к одному из проектов. Проект имеет связь с пользователем, который его создал.

-- Связи:

-- автор: пользователь, создавший проект;

-- 5.2. Задача

-- Центральная сущность всего сайта.

-- Поля:
-- дата создания: дата и время, когда задача была создана;
-- статус: число (1 или 0), означающее, была ли выполнена задача. По умолчанию ноль;
-- название: задаётся пользователем;
-- файл: ссылка на файл, загруженный пользователем;
-- срок: дата, до которой задача должна быть выполнена.

-- Связи:
-- автор: пользователь, создавший задачу;
-- проект: проект, которому принадлежит задача.

-- 5.3. Пользователь

-- Представляет зарегистрированного пользователя.

-- Поля:
-- дата регистрации: дата и время, когда этот пользователь завел аккаунт;
-- email;
-- имя;
-- пароль: хэшированный пароль пользователя.
