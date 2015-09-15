<?php
    //5.5.12 current PHP version
    
    //START SLIM CONFIGURATION
    require 'Slim/Slim.php';
    \Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();






    ///////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////DATABASE SETTINGS////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    //'localhost', 'theblack_admin', 'Blacksoul2015', 'theblack_blacksoul'
    //'67.225.221.232:3306', 'theblack_admin', 'Blacksoul2015', 'theblack_blacksoul'
    // 'localhost:3306', 'root', '', 'blacksoul' 
    function connectToDataBase()
    {
        return mysqli_connect( 'localhost:3306', 'root', '', 'database'   ); 
        
    }









    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////DATABASE POINTS/////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

    //GET ALL CARS
    $app->get('/get-allcars', function () {     

        $db  = connectToDataBase();
        mysqli_set_charset($db, "utf8");

        $sql = 'SELECT * FROM cars'; 
        $result = mysqli_query($db,$sql); 


        $rows = array(); 

            while($row = mysqli_fetch_array($result)) 
            { 
                $rows[] = $row;
            }

        mysqli_close($db); 

        //Creamos el JSON
        $json_string = json_encode($rows);
        echo $json_string;      

    });








    $app->get('variable/:name', function ($name) {
        echo "Hello, $name";
    });

    $app->get('/example', function () {
        echo "INDEX";
    });




    $app->run();
?>