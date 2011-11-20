<?php
    //constants
    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = 'amine';
    $dbDatabase = 'mydb';
    //make connection
    $server = mysql_connect($dbHost, $dbUsername,$dbPassword);
    $connection = mysql_select_db($dbDatabase, $server);
?>
