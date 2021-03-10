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

function getTasksToShow() {
    $tasks = getSqlData()['tasks'];

    if (empty($_GET)) {
       return $tasks;
    }

    try {
        if (intval($_GET['project_id']) < 0 || !is_numeric($_GET['project_id'])) {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            exit;
        }

    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $tasks = array_filter($tasks, function($task) {
        return intval($task['project_id']) === intval($_GET['project_id']);
    });

    return $tasks;
}

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

function isTaskImportant(array $tasksList) {
    $currentTime = time();

    foreach ($tasksList as $taskKey => $currentTask) {

        $dueTimeinHours = floor((strtotime($currentTask['due_date']) - $currentTime) / 3600);

        $currentTask['isImportant'] = true;

        if ($dueTimeinHours > 24 || strtotime($currentTask['due_date']) == null) {
            $currentTask['isImportant'] =  false;
        }
        $tasksList[$taskKey] = $currentTask;
    }
    return $tasksList;
}

function isProjectActive(array $projects) {

    foreach ($projects as $projectKey => $currentProject) {
        $projects[$projectKey]['activeProject'] = false;
        if (empty($_GET)) {
            $projects[$projectKey]['activeProject'] = false;
         } elseif (intval($_GET['project_id']) === intval($currentProject['id'])) {
            $projects[$projectKey]['activeProject'] = true;
          }
    }
    return $projects;
}

function getTasksCount(array $projectList, array $tasksList) {

    $idNameProjects = array_combine(
        array_column($projectList, 'id'),
        array_column($projectList, 'name')
        );

    foreach ($projectList as $projectKey => $currentProject) {
        $taskCounter = 0;

        foreach ($tasksList as $taskKey => $task) {

            if ($idNameProjects[$task['project_id']] === $currentProject['name']) {
                $taskCounter++;
            }
        }
        $projectList[$projectKey]['count'] = $taskCounter;
    }
    return $projectList;
}

function getDataUpdate() {
    $data = getSqlData();
    $tasksToShow = getTasksToShow();

    $filteredProjects = getFilterArray($data['projects']);
    $filteredTasks = getFilterArray($data['tasks']);

    $projectsArrayWithCountedTasks = getTasksCount($filteredProjects, $filteredTasks);

    $tasksToShow = isTaskImportant($tasksToShow);
    $projectsToShow = isProjectActive($projectsArrayWithCountedTasks);

    return [
        'projects' => $projectsToShow,
        'tasks' => $tasksToShow
    ];
}

$data = getDataUpdate();

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
