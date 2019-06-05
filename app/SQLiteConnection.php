<?php
namespace App;

/**
* SQLite connnection
*/
class SQLiteConnection {
    /**
    * PDO instance
    * @var type
    */
    private $pdo;

    /**
    * return in instance of the PDO object that connects to the SQLite database
    * @return \PDO
    */
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . Config::PATH_TO_SQLITE_FILE);
        }
        return $this->pdo;
    }

    public function makeSchema($file) {
        $schema_file = file_get_contents($file);

        $schema_rows = explode(";", $schema_file);
        foreach ($schema_rows as $command) {
            $command = trim($command);
            if ('' == $command) {
                continue;
            }
            $this->pdo->exec($command);
        }
    }

    public function query($query, $params, $type = \PDO::FETCH_ASSOC) {
        $query = trim($query);
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll($type);
    }

    public function getConnection() {
        return $this->pdo;
    }
}
