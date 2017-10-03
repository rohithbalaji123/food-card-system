<?php
    
    /**
     * function to add a customer in customers table
     */
    function addCustomer() {

        if(!isset($_POST["name"]) || !isset($_POST["phone_number"]) || !isset($_POST["RFIDcard_id"])) {
            throw new Exception("Parameters missing.");
        }

        $name = $_POST["name"];
        $phone_number = $_POST["phone_number"];
        $RFIDcard_id = $_POST["rfidcard_id"];

        $conn = open_db_conn();

        $stmt = $conn->prepare("INSERT INTO customers (name, phone_number, RFIDcard_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone_number, $RFIDcard_id);

        $stmt->execute();
        $stmt->close();

        close_db_conn($conn);

        return true;
    }