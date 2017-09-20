<?php 
    
    /**
     * function to create the database if it doesn't already exist
     * @param  mysqli   $conn       mysqli connection object
     * @param  string   $dbname     name of the database to be created
     * @return boolean              true, if database is created successfully
     */
    function createDatabase($conn, $dbname) {

        // Create the database if it doesn't already exist
        $query = "CREATE DATABASE IF NOT EXISTS `".$dbname."`;";
        if(! mysqli_query($conn, $query)) {
            throw new Exception("Error creating database " . mysqli_error($conn));
        }

        return true;
    }


    /**
     * function to open a connection with mysql database using credentials stored in .env file
     * @return  mysqli  object of class mysqli
     */
    function open_db_conn() {

        $env_variables = file(".env");

        foreach($env_variables as $env_variable) {
            $temp = explode("=", $env_variable);

            // to trim the extar space added by explode function for god knows why...
            $env[$temp[0]] = trim($temp[1]);
        }

        // Initialize environment variables
        $dbhost = $env["DB_HOST"];
        $dbuser = $env["DB_USERNAME"];
        $dbpass = $env["DB_PASSWORD"];
        $dbname = $env["DB_NAME"];

        // Establish a connect with mysql
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
        if (mysqli_connect_errno()) {
            throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        // Create the database if not already exists
        try {
            createDatabase($conn, $dbname);
        }
        catch(Exception $e) {
            throw $e;
        }

        // Connect the database to the mysqli object
        if(!mysqli_select_db($conn, $dbname)) {
            throw new Exception("Database connection failure.");
        }

        return $conn;    
    }

    /**
     * function to close mysql database connection
     * @param  mysqli   $conn   object of class mysqli
     * @return boolean          true if connection is closed successfully
     */
    function close_db_conn($conn) {
        try {
            mysqli_close($conn);
        }
        catch(Exception $e) {
            throw $e;
        }

        return true;
    }
