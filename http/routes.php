<?php
    
    require __DIR__ . '/../databases/db_connection.php';
    
    require '../http/controllers/vendorController.php';
    require '../http/controllers/rfidCardController.php';
    require '../http/controllers/customerController.php';
    require '../http/controllers/itemsController.php';

    /**
     * A helper function to returns response in json format
     * @param  string       $message    response message
     * @param  string       $status     response header status
     * @return json_string              json_encoded response string
     */
    function getJSONResponse($message, $status) {
        header('HTTP/1.0 ' . $status);
        header('Content-Type: application/json');
        return json_encode(array("message" => $message));
    }

    function redirect($url) {
        if(headers_sent()) {
            die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
        }
        else {
            header('Location: ' . $url);
            die();
        }
    }

    /**
     * function which redirects the routes to its corressponding functions
     * @param  string   $uri        uri route
     * @param  string   $method     request method
     */
    function executeFunctionByUri($uri, $method) {
        
        
        // GET routes
        if($method == 'GET') {
            switch($uri) {
                // Homepage
                case '/':
                    require '../views/vendorLogin.html';
                    break;

                // Vendor login page
                case '/vendor/login':
                    require '../views/vendorLogin.html';
                    break;

                // Vendor registration page
                case '/vendor/register':
                    require '../views/vendorRegister.html';
                    break;

                // Vendor Logout Page
                case '/vendor/logout':
                    require '../views/vendorLogout.html';
                    break;

                // Menu item adding page
                case '/vendor/item/add':
                    if(!isVendorLoggedIn()) {
                        redirect('/vendor/login');
                        return;
                    }
                    require '../views/vendorItemAdd.html';
                    return;

                // RFID Card registration page
                case '/rfidcard/register':
                    require '../views/rfidcardRegister.html';
                    break;

                // Customer registration page
                case '/customer/register':
                    require '../views/customerRegister.html';
                    break;

                default:
                    header('HTTP/1.0 404 Not Found');
                    require '../views/404.html';
                    break;
            }
        }
        // POST routes
        else if($method == 'POST') {
            switch($uri) {
                // Vendor authentication
                case '/vendor/login':
                    try {
                        authenticateVendor();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
                    echo getJSONResponse('Successful...', '200 OK');
                    break;

                // Vendor logout route
                case '/vendor/logout':
                    try {
                        logoutVendor();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
                    echo getJSONResponse('Successful...', '200 OK');
                    break;

                // Vendor registration route
                case '/vendor/register':
                    try {
                        addVendor();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getJSONResponse('Successful...', '200 OK');
                    break;

                // Vendor menu item adding route
                case '/vendor/item/add':
                    try {
                        addMenuItem();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getJSONResponse('Successful...', '200 OK');
                    break;

                // RFIDCard register route
                case '/rfidcard/register':
                    try {
                        addRFIDCard();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getJSONResponse('Successful...', '200 OK');
                    break;

                // Customer registration route
                case '/customer/register':
                    try {
                        addCustomer();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getJSONResponse('Successful...', '200 OK');
                    break;

                default:
                    header('HTTP/1.0 404 Not Found');
                    require '../views/404.html';
                    break;
            }
        }
        else {
            header('HTTP/1.0 404 Not Found');
            require '../views/404.html';
        }
    }
