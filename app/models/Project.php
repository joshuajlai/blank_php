<?php

namespace App\Models;

class Project {

    /**
    * PDO object
    * @var \PDO
    */
    private $pdo;

    private $connection;

    /**
    * Initialize the object with a specified PDO object
    * @param \PDO $pdo
    */
    public function __construct(\App\SQLiteConnection $connection) {
        $this->pdo = $connection->getConnection();
        $this->connection = $connection;
    }

    /**
    * Insert a new project into the projects table
    * @param string $projectName
    * @return the id of the new project
    */
    public function insertProject($projectName) {
        $sql = 'INSERT INTO projects(project_name) VALUES(:project_name)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':project_name', $projectName);
        $stmt->execute();

        $projectId = $this->pdo->lastInsertId();

        return $this->findProjectById($projectId);
    }

    public function findProject($projectName) {
        $query = "
            select *
            from projects
            where project_name = :projectName
        ";
        $params = [
            ':projectName' => $projectName,
        ];
        $data = $this->connection->query($query, $params);

        return current($data);
    }

    public function findProjectById($projectId) {
        $query = "
            select *
            from projects
            where project_id = :projectId
        ";
        $params = [
            ':projectId' => $projectId,
        ];
        $data = $this->connection->query($query, $params);

        return current($data);
    }
}
