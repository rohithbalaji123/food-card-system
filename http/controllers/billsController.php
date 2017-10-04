<?php
    
    function addBill() {
        
        if(!isset($_POST["rfidcard_id"]) || !isset($_POST["bill_details"]) || !isset($_POST["amount"])) {
            throw new Exception("Parameters missing.");
        }

        $RFIDCard_id = $_POST["rfidcard_id"];
        $vendor_id = $_SESSION["vendorId"];
        $amount = $_POST["amount"];
        $bill_details = $_POST["bill_details"];
        $bill_details = json_decode($bill_details, true);

        $RFIDCard_id = trim($RFIDCard_id, '"');

        $conn = open_db_conn();
        $stmt = $conn->prepare("SELECT id FROM customers WHERE RFIDcard_id = ?");
        $stmt->bind_param("s", $RFIDCard_id);
        $stmt->execute();
        $stmt->bind_result($customer_id);
        $stmt->fetch();
        $stmt->close();
        if(!$customer_id) {
            throw new Exception("Invalid card.");
        }


        $stmt = $conn->prepare("SELECT balance FROM customers WHERE id = ? LIMIT 1");
        $stmt->bind_param("s", $customer_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        if(floatval($balance) < floatval($amount)) {
            throw new Exception("Insufficient balance.");
        }
        $stmt->close();

        
        $stmt = $conn->prepare("UPDATE customers SET balance = balance-? WHERE RFIDcard_id = ? LIMIT 1");
        $stmt->bind_param("ds", floatval($amount), $RFIDCard_id);
        $stmt->execute();
        $stmt->close();


        $stmt = $conn->prepare("INSERT INTO bills (customer_id, vendor_id, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $customer_id, $vendor_id, $amount);
        $stmt->execute();
        $stmt->close();

        
        $bill_id = $conn->insert_id;
        close_db_conn($conn);
        foreach ($bill_details as $index => $bill_detail) {
            addBillDetails($bill_detail["item_id"], $bill_detail["quantity"], $bill_detail["price"], $bill_id);
        }

        return floatval($balance) - floatval($amount);
    }