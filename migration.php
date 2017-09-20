<?php
    
    require(__DIR__ . '/db_connection.php');
    
    // The list of tables to be created
    $tables = array(
        // table_name => columns ( coumn_name => column_type)
        "user"  =>  array(
                        "id"    =>  "INT PRIMARY KEY AUTO_INCREMENT",
                        "name"  =>  "VARCHAR(20) NOT NULL",
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
            $query = "CREATE TABLE `".$table."`( ";
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