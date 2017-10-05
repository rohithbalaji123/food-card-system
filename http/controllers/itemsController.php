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

        $conn = odbc_connect("Driver={MySQL};Server=127.0.0.1;Database=food-card-system;", "root", "root");

        $stmt = odbc_prepare($conn, "INSERT INTO items (name, price, vendor_id) VALUES (?, ?, ?)");
        $t = odbc_execute($stmt, array($name, $price, $vendor_id));

        // $stmt->execute();
        // $stmt->close();

        odbc_close($conn);

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