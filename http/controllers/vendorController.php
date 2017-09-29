<?php

    function authenticateVendor() {
        
        if(!isset($_POST["username"]) || !isset($_POST["password"])) {
            throw new Exception("Parameters missing.");
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        $conn = open_db_conn();

        $stmt = $conn->prepare("SELECT password FROM vendors WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashedPasswordFromDB);
        mysqli_stmt_fetch($stmt);

        close_db_conn($conn);

        if(!password_verify($password, $hashedPasswordFromDB)) {
            throw new Exception("Username and password mismatch");
        }

        return true;
    }

    function addVendor() {

        if(!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["shop_name"]) || !isset($_POST["owner_name"]) || !isset($_POST["phone_number"])) {
            throw new Exception("Parameters missing.");
            
        }

        $username = $_POST["username"];
        $password = $_POST["password"];
        $shop_name = $_POST["shop_name"];
        $owner_name = $_POST["owner_name"];
        $phone_number = $_POST["phone_number"];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);


        $conn = open_db_conn();

        $stmt = $conn->prepare("INSERT INTO vendors (username, password, shop_name, owner_name, phone_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hashedPassword, $shop_name, $owner_name, $phone_number);

        $stmt->execute();
        $stmt->close();

        close_db_conn($conn);

        return true;
    }