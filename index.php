<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require('helpers.php');

$show_complete_tasks = rand(0, 1);

function getProjectSqlQuery() {
    return "SELECT id, name FROM project";
}

function getTaskSqlQuery() {
    return "SELECT id, project_id, isDone, name, due_date FROM task";
}

function getSqlData() {
    $connect = mysqli_connect("localhost", "root", "root","doingsdone_db");

    $resultProjects = mysqli_query($connect, getProjectSqlQuery());
    $resultTasks = mysqli_query($connect, getTaskSqlQuery());

    $projects = mysqli_fetch_all($resultProjects, MYSQLI_ASSOC);
    $tasks = mysqli_fetch_all($resultTasks, MYSQLI_ASSOC);

    mysqli_close($connect);

    return [
        'projects' => $projects,
        'tasks' => $tasks
    ];
}

$data = getSqlData();

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

function getTasksByProjects($array) {
    $tasksByProjects = [];
    foreach ($array as $key => $value) {

        if (isset($_GET[$value['project_id']])) {
            array_push($tasksByProjects, $value);
          } elseif (empty($_GET)) {
            $tasksByProjects = $array;
          }
    }
    return $tasksByProjects;
}

function isProjectActive($project) {

    if (isset($_GET[$project])) {
       return true;
    }
    return false;
}

function getUpdatedArray(array $projectList, array $tasksList) {

    foreach ($tasksList as $taskKey => $currentTask) {
        $tasksList[$taskKey]['isImportant'] = isTaskImportant($currentTask['due_date']);
    }

    foreach ($projectList as $projectKey => $currentProject) {
        $projectList[$projectKey]['count'] = getTasksCount($projectList, $tasksList, $currentProject['name']);
        $projectList[$projectKey]['activeProject'] = isProjectActive($currentProject['id']);
    }

    $tasksList = getTasksByProjects($tasksList);

    return [$projectList, $tasksList];
}

list($data['projects'], $data['tasks']) = getUpdatedArray($data['projects'], $data['tasks']);
getUpdatedArray($data['projects'], $data['tasks']);
getFilterArray($data['projects']);
getFilterArray($data['tasks']);

$mainContent = include_template('main.php',
[
    "arrayProjects" => $data['projects'],
    "arrayTasks" => $data['tasks'],
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
