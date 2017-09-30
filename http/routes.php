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
    function getResponse($message, $status) {
        header('HTTP/1.0 ' . $status);
        header('Content-Type: application/json');
        return json_encode(array("message" => $message));
    }

    function redirect($url) {
        if (headers_sent()) {
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
        
        if($method == 'GET') {
            switch($uri) {
                case '/':
                    require '../views/vendorLogin.html';
                    break;

                case '/vendor/login':
                    require '../views/vendorLogin.html';
                    break;

                case '/vendor/register':
                    require '../views/vendorRegister.html';
                    break;

                case '/vendor/logout':
                    require '../views/vendorLogout.html';
                    break;

                case '/vendor/item/add':
                    if(!isVendorLoggedIn()) {
                        redirect('/vendor/login');
                        return;
                    }
                    require '../views/vendorItemAdd.html';
                    return;

                case '/rfidcard/register':
                    require '../views/rfidcardRegister.html';
                    break;

                case '/customer/register':
                    require '../views/customerRegister.html';
                    break;

                default:
                    header('HTTP/1.0 404 Not Found');
                    require '../views/404.html';
                    break;
            }
        }
        else if($method == 'POST') {
            switch($uri) {
                case '/vendor/login':
                    try {
                        authenticateVendor();
                    }
                    catch(Exception $e) {
                        echo getResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
                    echo getResponse('Successful...', '200 OK');
                    break;

                case '/vendor/logout':
                    try {
                        logoutVendor();
                    }
                    catch(Exception $e) {
                        echo getResponse($e->getMessage(), '401 Unauthorized');
                        return;
                    }
                    echo getResponse('Successful...', '200 OK');
                    break;

                case '/vendor/register':
                    try {
                        addVendor();
                    }
                    catch(Exception $e) {
                        echo getResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getResponse('Successful...', '200 OK');
                    break;

                case '/vendor/item/add':
                    try {
                        addMenuItem();
                    }
                    catch(Exception $e) {
                        echo getResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getResponse('Successful...', '200 OK');
                    break;

                case '/rfidcard/register':
                    try {
                        addRFIDCard();
                    }
                    catch(Exception $e) {
                        echo getResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getResponse('Successful...', '200 OK');
                    break;

                case '/customer/register':
                    try {
                        addCustomer();
                    }
                    catch(Exception $e) {
                        echo getResponse($e->getMessage(), '400 Bad Request');
                        return;
                    }
                    echo getResponse('Successful...', '200 OK');
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
