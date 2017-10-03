<?php

    function addBillDetails($item_id, $quantity, $price, $bill_id) {
        $conn = open_db_conn();

        $stmt = $conn->prepare("INSERT INTO bill_details (item_id, quantity, price, bill_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $item_id, $quantity, $price, $bill_id);

        $stmt->execute();
        $stmt->close();

        close_db_conn($conn);

        return true;
    }