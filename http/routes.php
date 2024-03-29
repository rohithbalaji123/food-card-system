<?php
    
    require __DIR__ . '/../databases/db_connection.php';
    
    require '../http/controllers/vendorController.php';
    require '../http/controllers/rfidCardController.php';
    require '../http/controllers/customerController.php';
    require '../http/controllers/itemsController.php';
    require '../http/controllers/billsController.php';
    require '../http/controllers/billDetailsController.php';

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
                    if(!isVendorLoggedIn()) {
                        redirect('/vendor/login');
                        return;
                    }
                    redirect('/vendor/dashboard');
                    return;

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

                // Vendor dashboard which shows bills and menu items
                case '/vendor/dashboard':
                    if(!isVendorLoggedIn()) {
                        redirect('/vendor/login');
                        return;
                    }
                    require '../views/vendorDashboard.html';
                    return;

                case '/vendor/details':
                    if(!isVendorLoggedIn()) {
                        echo getJSONResponse("Access denied.", '401 Unauthorized');
                        return;
                    }
                    try {
                        echo getJSONResponse(getVendorDetails(), '200 Successful');
                        return;
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    break;

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
                    if(!isVendorLoggedIn()) {
                        echo getJSONResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
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

                case '/vendor/bill/add':
                    if(!isVendorLoggedIn()) {
                        echo getJSONResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
                    try {
                        $balance = addBill();
                    }
                    catch(Exception $e) {
                        echo getJSONResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getJSONResponse('Successful... Your balance is ' . $balance, '200 OK');
                    break;

                // Vendor menu item adding route
                case '/vendor/item/add':
                    if(!isVendorLoggedIn()) {
                        echo getJSONResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
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
