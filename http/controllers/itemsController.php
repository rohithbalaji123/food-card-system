<?php

    /**
     * Function to add a menu item to items table
     */
    function addMenuItem() {
        if(!isVendorLoggedIn()) {
            throw new Exception("Access denied.");
        }

        if(!isset($_POST["name"]) || !isset($_POST["price"])) {
            throw new Exception("Parameters missing.");
        }

        $name = $_POST["name"];
        $price = $_POST["price"];
        $vendor_id = $_SESSION["vendorId"];

        $conn = open_db_conn();

        $stmt = $conn->prepare("INSERT INTO items (name, price, vendor_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $price, $vendor_id);

        $stmt->execute();
        $stmt->close();

        close_db_conn($conn);

        return true;
    }