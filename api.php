<?php
    //5.5.12 current PHP version
    
    //START SLIM CONFIGURATION
    require 'Slim/Slim.php';
    \Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();



        $app->get('variable/:name', function ($name) {
            echo "Hello, $name";
        });

        $app->get('/example', function () {
            echo "INDEX";
        });




    $app->run();
?>