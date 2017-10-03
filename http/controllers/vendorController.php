<?php
    
    /**
     * Helper function to find whether a vendor is loggedin or not
     * @return boolean true or false depending on whether a vendor is logged in or not respectively
     */
    function isVendorLoggedIn() {
        return  ( isset($_SESSION["username"]) && 
                  isset($_SESSION["vendorId"]) && 
                 !empty($_SESSION["username"]) && 
                 !empty($_SESSION["vendorId"])
                );        
    }

    /**
     * Function to authenticate vendor after verifying the credentials and set session if authenticated
     */
    function authenticateVendor() {
        
        if(!isset($_POST["username"]) || !isset($_POST["password"])) {
            throw new Exception("Parameters missing.");
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        $conn = open_db_conn();

        $stmt = $conn->prepare("SELECT id, password FROM vendors WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);

        $stmt->execute();
        $stmt->bind_result($vendorId, $hashedPasswordFromDB);
        $stmt->fetch();
        $stmt->close();

        close_db_conn($conn);

        if(!password_verify($password, $hashedPasswordFromDB)) {
            throw new Exception("Username and password mismatch");
        }

        $_SESSION["username"] = $username;
        $_SESSION["vendorId"] = $vendorId;
        return true;
    }

    /**
     * Function to logout vendor by unsetting sessions
     */
    function logoutVendor() {
        $_SESSION = array();
        session_destroy();
    }

    /**
     * Function to add a vandor to vendors table
     */
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