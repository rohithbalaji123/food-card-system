<?php

    /**
     * Function to add a menu item to items table
     */
    function addMenuItem() {

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

    function getItemByVendorId($vendorId) {

        $conn = open_db_conn();

        $itemDetails = array();

        $stmt = $conn->prepare("SELECT id, name, price FROM items WHERE vendor_id = ?");
        $stmt->bind_param("s", $vendorId);

        $stmt->execute();
        $stmt->bind_result($id, $name, $price);
        
        while($stmt->fetch()) {
            array_push($itemDetails, array("id" => $id, "name" => $name, "price" => $price));
        }
        
        $stmt->close();
        close_db_conn($conn);

        return $itemDetails;
    }