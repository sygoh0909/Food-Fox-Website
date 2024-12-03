<?php
function connection() {
    static $connection = null;

    if($connection === null) {
        $config = [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'db' => 'foodfoxdb',
        ];

        $connection = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['db']);

        if (!$connection) {
            error_log('Could not connect to database.' . mysqli_connect_error());
            exit;
        }
    }

    return $connection;
}