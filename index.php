<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$arrayProjects = [
    ['id' => 1,
    'name' => "Входящие"],
    ['id' => 2,
    'name' => "Учеба"],
    ['id' => 3,
    'name' => "Работа"],
    ['id' => 4,
    'name' => "Домашние дела"],
    ['id' => 5,
    'name' => "Авто"]
];

$idNameProjects = array_combine(
    array_column($arrayProjects, 'id'),
    array_column($arrayProjects, 'name')
    );

$arrayTasks = [
    [
        'id' => 1,
        'name' => 'Собеседование в IT компании',
        'date' => '01.12.2019',
        'category' => 3,
        'isDone' => false
    ],
    [
        'id' => 2,
        'name' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'category' => 3,
        'isDone' => false
    ],
    [
        'id' => 3,
        'name' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'category' => 2,
        'isDone' => true
    ],
    [
        'id' => 4,
        'name' => 'Встреча с другом',
        'date' => '22.12.2019',
        'category' => 1,
        'isDone' => false
    ],
    [
        'id' => 5,
        'name' => 'Купить корм для кота',
        'date' => null,
        'category' => 4,
        'isDone' => false
    ],
    [
        'id' => 6,
        'name' => 'Заказать пиццу',
        'date' => null,
        'category' => 4,
        'isDone' => false
    ],
];

function getTasksCount($projectList, $projectName) {
    $i = 0;
    foreach ($projectList as $project) {
        global $idNameProjects;
        if ($idNameProjects[$project['category']] === $projectName) {
            $i++;
        }
    }
    return $i;
}

$allDataArray = [
    "show_complete_tasks" => $show_complete_tasks,
    "title" => "Дела в порядке",
    "userName" => "Константин",
    "arrayProjects" => $arrayProjects,
    "idNameProjects" => $idNameProjects,
    "arrayTasks" => $arrayTasks
];

require('helpers.php');
include_template('main.php', $allDataArray);
print(include_template('layout.php', $allDataArray));

?>
