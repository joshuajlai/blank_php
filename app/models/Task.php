<?php

namespace App\Models;

class Task {
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
    public function __construct(\App\SQLiteConnection $connection) {
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
    public function insertTask($taskName, $startDate, $completedDate, $completed, $projectId) {
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

    public function findTask($taskName, $projectId) {
        $query = "
            select *
            from tasks
            where task_name = :name
            and project_id = :projectId
        ";
        $params = [
            ':name' => $taskName,
            ':projectId' => $projectId,
        ];
        $data = $this->connection->query($query, $params);

        return current($data);
    }

    public function findTaskById($taskId) {
        $query = "
            select *
            from tasks
            where task_id = :taskId
        ";
        $params = [
            ':taskId' => $taskId,
        ];
        $data = $this->connection->query($query, $params);

        return current($data);
    }

    public function updateTask($taskId, $properties) {
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
}
