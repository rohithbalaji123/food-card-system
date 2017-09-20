<?php
    
    require(__DIR__ . '/db_connection.php');

    try {
        $conn = open_db_conn();
    }
    catch(Exception $e) {
        die($e);
    }

    echo "Connection successful...";