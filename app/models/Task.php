<?php

namespace App\Models;

class Task extends Base
{
    /**
    * PDO object
    * @var \PDO
    */
    private $pdo;

    private $connection;

    /**
    * Initialize the object with a specified PDO object
    * @param \PDO $connection
    */
    public function __construct(\App\SQLiteConnection $connection)
    {
        $this->connection = $connection;
        $this->pdo = $connection->getConnection();
    }

    /**
    * Insert a new task into the tasks table
    * @param type $taskName
    * @param type $startDate
    * @param type $completedDate
    * @param type $completed
    * @param type $projectId
    * @return int id of the inserted task
    */
    public function insertTask(
        $taskName,
        $startDate,
        $completedDate,
        $completed,
        $projectId
    ) {
        $sql = "
            INSERT INTO tasks
                (task_name,start_date,completed_date,completed,project_id)
            VALUES
                (:task_name,:start_date,:completed_date,:completed,:project_id)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':task_name' => $taskName,
            ':start_date' => $startDate,
            ':completed_date' => $completedDate,
            ':completed' => $completed,
            ':project_id' => $projectId,
        ]);

        $taskId = $this->pdo->lastInsertId();

        return $this->findTaskById($taskId);
    }

    public function findTasks($properties)
    {
        $query = "
            select *
            from tasks
        ";

        $query .= $this->generateWhere($properties);
        return $this->connection->query($query, array_values($properties));
    }

    public function findTask($taskName, $projectId) {
        $data = $this->findTasks([
            'task_name' => $taskName,
            'project_id' => $projectId,
        ]);

        return current($data);
    }

    public function findTaskById($taskId)
    {
        $data = $this->findTasks(['task_id' => $taskId]);
        return current($data);
    }

    public function updateTask($taskId, $properties)
    {
        $query = "
            UPDATE tasks
            SET
        ";
        $set_parts = [];
        foreach ($properties as $property => $value) {
            $set_parts[] = " {$property} = ? ";
        }
        $query .= implode(', ', $set_parts);
        $query .= 'where task_id = ?';
        $stmt = $this->pdo->prepare($query);
        $stmt_values = array_merge(array_values($properties), [$taskId]);
        $stmt->execute($stmt_values);

        return $this->findTaskById($taskId);
    }

    public function deleteTasks($properties)
    {
        $query = "
            delete
            from tasks
        ";
        $query .= $this->generateWhere($properties);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($properties));
    }
}
