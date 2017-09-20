<?php 
    
    /**
     * function to open a connection with mysql database using credentials stored in .env file
     * @return an object of class mysqli
     */
    function open_db_conn() {

        $env_variables = file(".env");

        foreach($env_variables as $env_variable) {
            $temp = explode("=", $env_variable);

            // to trim the extar space added by explode function for god knows why...
            $env[$temp[0]] = trim($temp[1]);
        }

        $dbhost = $env["DB_HOST"];
        $dbuser = $env["DB_USERNAME"];
        $dbpass = $env["DB_PASSWORD"];
        $dbname = $env["DB_NAME"];

        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        if (mysqli_connect_errno()) {
            throw new Exception("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        return $conn;    
    }

    /**
     * function to close mysql database connection
     * @param  object   $conn object of class mysqli
     * @return boolean  true if connection is closed successfully
     */
    function close_db_conn($conn) {
        try {
            mysqli_close($conn);
        }
        catch(Exception $e) {
            throw new Exception($e);
        }

        return true;
    }
