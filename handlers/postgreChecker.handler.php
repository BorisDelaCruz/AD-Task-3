<?php

function checkPostgreSQLConnection(): string {
    $host = "host.docker.internal"; 
    $port = "5112";
    $username = "user";
    $password = "password";
    $dbname = "mydatabase";

    $conn_string = "host=$host port=$port dbname=$dbname user=$username password=$password";

    $dbconn = pg_connect($conn_string);

    if (!$dbconn) {
        return "❌ Connection Failed: " . pg_last_error() . "  <br>";
    } else {
        pg_close($dbconn);
        return "✔️ PostgreSQL Connection  <br>";
    }
}

// Only output if this file is called directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    echo checkPostgreSQLConnection();
}

