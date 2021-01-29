-- ЗАПОЛНЕНИЕ ДАННЫХ ТАБЛИЦЫ ПРОЕКТЫ

INSERT INTO project SET name = 'Входящие';
INSERT INTO project SET name = 'Учеба';
INSERT INTO project SET name = 'Работа';
INSERT INTO project SET name = 'Домашние дела';
INSERT INTO project SET name = 'Авто';

-- ЗАПОЛНЕНИЕ ДАННЫХ ТАБЛИЦЫ ЗАДАЧИ

INSERT INTO task SET date_create = '07.02.2021',  name = 'Собеседование в IT компании', status = 0;
INSERT INTO task SET date_create = '15.01.2021',  name = 'Выполнить тестовое задание', status = 0;
INSERT INTO task SET date_create = '12.02.2021',  name = 'Сделать задание первого раздела', status = 1;
INSERT INTO task SET date_create = '14.01.2021',  name = 'Встреча с другом', status = 0;
INSERT INTO task SET name = 'Купить корм для кота', status = 0;
INSERT INTO task SET date_create = '24.01.2021',  name = 'Заказать пиццу', status = 0;

-- ЧТЕНИЕ ЗАПИСЕЙ ИЗ ПРОЕКТОВ

SELECT id, name, password FROM project;

-- ЧТЕНИЕ ЗАПИСЕЙ ИЗ ЗАДАЧ

SELECT id, date_create, status, name, url, due_date  FROM task;


