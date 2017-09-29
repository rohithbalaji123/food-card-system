<?php
    
    function executeFunctionByUri($uri) {
        switch($uri) {
            case '/':
                require 'views/login.html';
                break;
                
            default:
                header('HTTP/1.0 404 Not Found');
                require 'views/404.html';
                break;
        }
    }
