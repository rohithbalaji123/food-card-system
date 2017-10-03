<?php
    
    function addBill() {
        
        if(!isset($_POST["rfidcard_id"]) || !isset($_POST["bill_details"]) || !isset($_POST["amount"])) {
            throw new Exception("Parameters missing.");
        }

        $RFIDCard_id = $_POST["rfidcard_id"];
        $vendor_id = $_SESSION["vendorId"];
        $amount = $_POST["amount"];
        $bill_details = $POST["bill_details"];


        $conn = open_db_conn();
        $stmt = $conn->prepare("SELECT id FROM customers WHERE RFIDcard_id = ? LIMIT 1");
        $stmt->bind_param("s", $RFIDCard_id);
        $stmt->execute();
        $stmt->bind_result($customer_id);
        if(!$stmt->num_rows) {
            throw new Exception("Invalid card.");
        }
        $stmt->fetch();
        $stmt->close();


        $stmt = $conn->prepare("SELECT balance FROM customers WHERE id = ? LIMIT 1");
        $stmt->bind_param("s", $customer_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        if((float)$balance < (float)$amount) {
            throw new Exception("Insufficient balance.");
        }
        $stmt->close();

        
        $stmt = $conn->prepare("UPDATE customers SET balance - balance - " . $amount . " WHERE id = ? LIMIT 1");
        $stmt->bind_param("s", $customer_id);
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

        return true;
    }