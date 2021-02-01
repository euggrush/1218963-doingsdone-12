<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require('helpers.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$con = mysqli_connect("localhost", "root", "root","doingsdone_db");
$sqlTasks = "SELECT name FROM task";
$sqlProjects = "SELECT id, name FROM project";

$resultTasks = mysqli_query($con, $sqlTasks);
$resultProjects = mysqli_query($con, $sqlProjects);

$arrayProjects = mysqli_fetch_all($resultProjects, MYSQLI_ASSOC);
$arrTasks = mysqli_fetch_all($resultTasks, MYSQLI_ASSOC);

// $arrayProjects = [
//     ['id' => 1,
//     'name' => "Входящие"],
//     ['id' => 2,
//     'name' => "Учеба"],
//     ['id' => 3,
//     'name' => "Работа"],
//     ['id' => 4,
//     'name' => "Домашние дела"],
//     ['id' => 5,
//     'name' => "Авто"]
// ];

$arrayTasks = [
    [
        'id' => 1,
        'name' => 'Собеседование в IT компании',
        'date' => '07.02.2021',
        'category' => 3,
        'isDone' => false
    ],
    [
        'id' => 2,
        'name' => 'Выполнить тестовое задание',
        'date' => '15.01.2021',
        'category' => 3,
        'isDone' => false
    ],
    [
        'id' => 3,
        'name' => 'Сделать задание первого раздела',
        'date' => '12.02.2021',
        'category' => 2,
        'isDone' => true
    ],
    [
        'id' => 4,
        'name' => 'Встреча с другом',
        'date' => '14.01.2021',
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
        'date' => '24.01.2021',
        'category' => 4,
        'isDone' => false
    ],
];

function getFilterArray(array $arrayToFilter) {

    foreach ($arrayToFilter as $arrayKey => $arrayItem) {
        foreach ($arrayItem as $itemKey => $ItemValue) {
            switch (gettype($ItemValue)) {
                case 'string':
                $arrayItem[$itemKey] = htmlspecialchars($ItemValue);
                break;
            }
        }
        $arrayToFilter[$arrayKey] = $arrayItem;
    }
    return $arrayToFilter;
}

function getTasksCount(array $projectList, array $tasksList, $projectName) {

    $idNameProjects = array_combine(
        array_column($projectList, 'id'),
        array_column($projectList, 'name')
        );

    $taskCounter = 0;

    foreach ($tasksList as $taskKey => $task) {

        if ($idNameProjects[$task['category']] === $projectName) {
            $taskCounter++;
        }
    }
    return $taskCounter;
}

function isTaskImportant($date) {
    $currentTime = time();
    $dueTimeinHours = floor((strtotime($date) - $currentTime) / 3600);
    if ($dueTimeinHours > 24 || strtotime($date) == null) {
        return false;
    }
    return true;
}

function getUpdatedArray(array $projectList, array $tasksList) {

    foreach ($tasksList as $taskKey => $currentTask) {
        $tasksList[$taskKey]['isImportant'] = isTaskImportant($currentTask['date']);
    }

    foreach ($projectList as $projectKey => $currentProject) {
        $projectList[$projectKey]['count'] = getTasksCount($projectList, $tasksList, $currentProject['name']);
    }

    return [$projectList, $tasksList];
}

list($arrayProjects, $arrayTasks) = getUpdatedArray($arrayProjects, $arrayTasks);
getUpdatedArray($arrayProjects, $arrayTasks);
getFilterArray($arrayProjects);
getFilterArray($arrayTasks);

$mainContent = include_template('main.php',
[
    "arrayProjects" => $arrayProjects,
    "arrayTasks" => $arrayTasks,
    "show_complete_tasks" => $show_complete_tasks
]);

$layoutContent = include_template('layout.php',
[
    "mainContent" => $mainContent,
    "title" => "Дела в порядке",
    "userName" => "Константин"
]
);

print($layoutContent);

?>
