-- ЗАПОЛНЕНИЕ ДАННЫХ ТАБЛИЦЫ ПРОЕКТЫ

INSERT INTO project SET name = 'Входящие';
INSERT INTO project SET name = 'Учеба';
INSERT INTO project SET name = 'Работа';
INSERT INTO project SET name = 'Домашние дела';
INSERT INTO project SET name = 'Авто';

-- ЗАПОЛНЕНИЕ ДАННЫХ ТАБЛИЦЫ ЗАДАЧИ

INSERT INTO task SET project_id = 3, name = 'Собеседование в IT компании', status = 0, due_date = '2021.02.15';
INSERT INTO task SET project_id = 3, name = 'Выполнить тестовое задание', status = 0, due_date = '2021.02.16';
INSERT INTO task SET project_id = 2, name = 'Сделать задание первого раздела', status = 1, due_date = '2021.02.18';
INSERT INTO task SET project_id = 1, name = 'Встреча с другом', status = 0, due_date = '2021.02.19';
INSERT INTO task SET project_id = 4, name = 'Купить корм для кота', status = 0;
INSERT INTO task SET project_id = 4, name = 'Заказать пиццу', status = 0, due_date = '2021.02.21';

-- ЧТЕНИЕ ЗАПИСЕЙ ИЗ ПРОЕКТОВ

SELECT id, name, password FROM project;

-- ЧТЕНИЕ ЗАПИСЕЙ ИЗ ЗАДАЧ

SELECT id, date_create, status, name, url, due_date  FROM task;


