<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require('helpers.php');
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

function getData() {
    $connect = mysqli_connect("localhost", "root", "root","doingsdone_db");

    $sqlProjects = "SELECT id, name FROM project";
    $sqlTasks = "SELECT id, project_id, isDone, name, due_date FROM task";

    $resultProjects = mysqli_query($connect, $sqlProjects);
    $resultTasks = mysqli_query($connect, $sqlTasks);

    $projects = mysqli_fetch_all($resultProjects, MYSQLI_ASSOC);
    $tasks = mysqli_fetch_all($resultTasks, MYSQLI_ASSOC);

    $arrayData = [
        'projects' => $projects,
        'tasks' => $tasks
    ];

    mysqli_close($connect);

    return $arrayData;
}

$arrayProjects = getData()['projects'];
$arrayTasks = getData()['tasks'];

// function getTasksArray() {
//     $sqlTasks = "SELECT id, project_id, isDone, name, due_date FROM task";
//     $resultTasks = mysqli_query(connectDb(), $sqlTasks);
//     return mysqli_fetch_all($resultTasks, MYSQLI_ASSOC);
// }

// function getProjectsArray() {
//     $sqlProjects = "SELECT id, name FROM project";
//     $resultProjects = mysqli_query(connectDb(), $sqlProjects);
//     return mysqli_fetch_all($resultProjects, MYSQLI_ASSOC);
// }

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

        if ($idNameProjects[$task['project_id']] === $projectName) {
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
        $tasksList[$taskKey]['isImportant'] = isTaskImportant($currentTask['due_date']);
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
