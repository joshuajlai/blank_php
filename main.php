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
