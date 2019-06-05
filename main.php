<?php
require 'vendor/autoload.php';

use App\SQLiteConnection;

try {
    $connection = new SQLiteConnection();
    $connection->connect();
    echo "Connected to the SQLite database successfully!\n";
} catch (Exception $exception) {
    echo "Whoops, could not connect to the SQLite database!\n";
}

$schema_file = 'schema.sql';
$connection->makeSchema($schema_file);
$projectDao = new \App\Models\Project($connection);
$taskDao = new \App\Models\Task($connection);

$projectName = 'project1';
$tasks = [
    [
        'name' => 'task1',
        'completed' => 0,
        'start_date' => '2019-01-01',
        'completed_date' => ''
    ]
];
$project = $projectDao->findProject($projectName);
if ($project == null) {
    $project = $projectDao->insertProject($projectName);
}
$task_results = [];
foreach ($tasks as $task) {
    $task_result = $taskDao->findTask($task['name'], $project['project_id']);
    if ($task_result == null) {
        $task_result = $taskDao->insertTask(
            $task['name'],
            $task['completed'],
            $task['start_date'],
            $task['completed_date'],
            $project_id
        );
    }
    $task_results[] = $task_result;
}

print("Project: " . print_r($project, true) . "\n");
print("Tasks: " . print_r($task_results, true) . "\n");

$updated_task = $taskDao->updateTask($task_results[0]['task_id'], ['completed' => 1]);
print("Updated Task: " . print_r($updated_task, true) . "\n");


function check_tables() {
    $query = "
        SELECT *
        FROM sqlite_master
        WHERE type = 'table'
        ORDER BY name
    ";
    $data = $connection->query($query);
}
