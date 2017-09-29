<?php
    
    require 'http/controllers/vendorController.php';

    function executeFunctionByUri($uri, $method) {
        
        if($method == 'GET') {
            switch($uri) {
                case '/':
                    require 'views/login.html';
                    break;

                case '/vendor/register':
                    require 'views/vendorRegister.html';
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
                        header('HTTP/1.0 401 Unauthorized');
                        header('Content-Type: application/json');
                        echo json_encode(array("message" => $e->getMessage()));
                        return;
                    }
                    header('Content-Type: application/json');
                    echo json_encode(array("message" => "Successful..."));
                    break;

                case '/vendor/register':
                    try {
                        addVendor();
                    }
                    catch(Exception $e) {
                        header('HTTP/1.0 400 Bad Request');
                        header('Content-Type: application/json');
                        echo json_encode(array("message" => $e->getMessage()));
                        return;
                    }
                    header('Content-Type: application/json');
                    echo json_encode(array("message" => "Successful..."));
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
