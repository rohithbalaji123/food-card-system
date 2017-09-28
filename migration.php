<?php
    
    require(__DIR__ . '/db_connection.php');
    
    // The list of tables to be created
    $tables = array(
        // table_name => columns ( coumn_name => column_type)
        "RFIDcards"  =>  array(
                        "id"            =>  "VARCHAR(15) PRIMARY KEY",
                        "expiry_date"   =>  "DATETIME NOT NULL",
                        "created_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP",
                        "updated_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
                    ),

        "customers" =>  array(
                        "id"            =>  "INT PRIMARY KEY AUTO_INCREMENT",
                        "name"          =>  "VARCHAR(30) NOT NULL",
                        "phone_number"  =>  "VARCHAR(15) NOT NULL",
                        "RFIDcard_id"   =>  "VARCHAR(15)",
                        "balance"       =>  "FLOAT DEFAULT 0",
                        "created_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP",
                        "updated_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
                        ""              =>  "FOREIGN KEY (RFIDcard_id) REFERENCES RFIDcards(id) ON DELETE CASCADE",
                    ),

        "vendors"   =>  array(
                        "id"            =>  "INT PRIMARY KEY AUTO_INCREMENT",
                        "shop_name"     =>  "VARCHAR(30) NOT NULL",
                        "owner_name"    =>  "VARCHAR(30) NOT NULL",
                        "phone_number"  =>  "VARCHAR(15) NOT NULL",
                        "created_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP",
                        "updated_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
                    ),

        "items"     =>  array(
                        "id"            =>  "INT PRIMARY KEY AUTO_INCREMENT",
                        "name"          =>  "VARCHAR(30) NOT NULL",
                        "price"         =>  "FLOAT NOT NULL",
                        "vendor_id"     =>  "INT NOT NULL",
                        "created_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP",
                        "updated_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
                        ""              =>  "FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE",
                    ),

        "bills"     =>  array(
                        "id"            =>  "INT PRIMARY KEY AUTO_INCREMENT",
                        "customer_id"   =>  "INT NOT NULL",
                        "vendor_id"     =>  "INT NOT NULL",
                        "amount"        =>  "FLOAT NOT NULL",
                        "created_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP",
                        "updated_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
                        ""              =>  "FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE",
                        ""              =>  "FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE",
                    ),

        "bill_details"=> array(
                        "id"            =>  "INT PRIMARY KEY AUTO_INCREMENT",
                        "item_id"       =>  "INT NOT NULL",
                        "quantity"      =>  "INT NOT NULL DEFAULT 1",
                        "price"         =>  "FLOAT NOT NULL",
                        "bill_id"       =>  "INT NOT NULL",
                        "created_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP",
                        "updated_at"    =>  "DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
                        ""              =>  "FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE",
                        ""              =>  "FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE",
                    ),
    );

    /**
     * Create all the tables from the tables array
     * @param  mysqli   $conn   mysqli connection object
     * @return bool             true, if table creation is successful
     */
    function createTables($conn) {
        global $tables;

        foreach($tables as $table => $columns) {
            $query = "CREATE TABLE IF NOT EXISTS `".$table."`( ";
            foreach($columns as $column => $desc) {
                $query .= $column . " " . $desc . ",";
            }

            // trim the last comma in $query
            $query = rtrim($query, ",");
            $query .= ");";

            if(!mysqli_query($conn, $query)) {
                throw new Exception("Table creation unsuccessful : " . mysqli_error($conn));
            }
        }

        return true;
    }

    /**
     * Run migrations to open connection and create databases and tables
     * @return bool     true, if the connection is established and tables are created
     */
    function migrate() {
        
        //Try opening a connection to database
        try {
            $conn = open_db_conn();
        }
        catch(Exception $e) {
            die($e);
        }

        //Creating tables
        try {
            createTables($conn);
        }
        catch(Exception $e) {
            die($e);
        }

        close_db_conn($conn);
        echo "Table creation successful.";
    }

    migrate();