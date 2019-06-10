<?php

namespace App\Models;

class Project extends Base
{

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
    public function __construct(\App\SQLiteConnection $connection)
    {
        $this->pdo = $connection->getConnection();
        $this->connection = $connection;
    }

    /**
    * Insert a new project into the projects table
    * @param string $projectName
    * @return the id of the new project
    */
    public function insertProject($projectName)
    {
        $sql = 'INSERT INTO projects(project_name) VALUES(:project_name)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':project_name', $projectName);
        $stmt->execute();

        $projectId = $this->pdo->lastInsertId();

        return $this->findProjectById($projectId);
    }

    public function findProjects($properties)
    {
        $query = "
            select *
            from projects
        ";
        $query .= $this->generateWhere($properties);

        return $this->connection->query($query, array_values($properties));
    }

    public function findProject($projectName)
    {
        $data = $this->findProjects(['project_name' => $projectName]);

        return current($data);
    }

    public function findProjectById($projectId)
    {
        $data = $this->findProjects(['project_id' => $projectId]);

        return current($data);
    }

    public function deleteProjects($properties)
    {
        $query = "
            delete
            from projects
        ";

        $query .= $this->generateWhere($properties);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($properties));
    }
}
