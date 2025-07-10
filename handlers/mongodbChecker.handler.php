<?php

function checkMongoDBConnection(): string {
    try {
        $mongo = new MongoDB\Driver\Manager("mongodb://host.docker.internal:27111");

        $command = new MongoDB\Driver\Command(["ping" => 1]);
        $mongo->executeCommand("admin", $command);

        return "✅ Connected to MongoDB successfully.  <br>";
    } catch (MongoDB\Driver\Exception\Exception $e) {
        return "❌ MongoDB connection failed: " . $e->getMessage() . "  <br>";
    }
}

// Only output if this file is called directly
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    echo checkMongoDBConnection();
}
    