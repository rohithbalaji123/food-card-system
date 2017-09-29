<?php
    
    function addRFIDCard() {

        if(!isset($_POST["rfidcard_id"])) {
            throw new Exception("Parameters missing.");
        }

        $rfidcard_id = $_POST["rfidcard_id"];

        $conn = open_db_conn();

        $stmt = $conn->prepare("INSERT INTO RFIDcards (id) VALUES (?)");
        $stmt->bind_param("s", $rfidcard_id);

        $stmt->execute();
        $stmt->close();

        close_db_conn($conn);

        return true;
    }