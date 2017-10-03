<?php
    
    function addBill() {
        
        if(!isset($_POST["customer_id"]) || !isset($_POST["bill_details"]) || !isset($_POST["amount"])) {
            throw new Exception("Parameters missing.");
        }

        $RFIDCard_id = $_POST["RFIDCard_id"];
        $vendor_id = $_SESSION["vendorId"];
        $amount = $_POST["amount"];
        $bill_details = $POST["bill_details"];

        $conn = open_db_conn();

        $stmt = $conn->prepare("SELECT id FROM customers WHERE RFIDcard_id = ? LIMIT 1");
        $stmt->bind_param("s", $RFIDCard_id);

        $stmt->execute();
        $stmt->bind_result($customer_id);
        $stmt->fetch($stmt);
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