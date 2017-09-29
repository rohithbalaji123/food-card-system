<?php
    
    require __DIR__ . '/../databases/db_connection.php';
    
    require 'http/controllers/vendorController.php';
    require 'http/controllers/rfidCardController.php';
    require 'http/controllers/customerController.php';

    function getResponse($message, $status) {
        header('HTTP/1.0 ' . $status);
        header('Content-Type: application/json');
        return json_encode(array("message" => $message));
    }

    function executeFunctionByUri($uri, $method) {
        
        if($method == 'GET') {
            switch($uri) {
                case '/':
                    require 'views/login.html';
                    break;

                case '/vendor/register':
                    require 'views/vendorRegister.html';
                    break;

                case '/rfidcard/register':
                    require 'views/rfidcardRegister.html';
                    break;

                case '/customer/register':
                    require 'views/customerRegister.html';
                    break;

                default:
                    header('HTTP/1.0 404 Not Found');
                    require 'views/404.html';
                    break;
            }
        }
        else if($method == 'POST') {
            switch($uri) {
                case '/vendor/auth':
                    try {
                        authenticateVendor();
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
                    require 'views/404.html';
                    break;
            }
        }
        else {
            header('HTTP/1.0 404 Not Found');
            require 'views/404.html';
        }
    }
