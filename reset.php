<?php

/*
 * reset simply truncates every table in the database to have no values
 */

include_once('dbConnect.php');
$link = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbDatabase);
$query = "Truncate Table order_book_pending;";
$query .= "Truncate Table order_book_active;";
$query .= "Truncate Table order_book_archive;";
$query .= "Truncate Table trade_book;";
$query .= "Truncate Table process_singleton_lock;";

$result = mysqli_multi_query($link, $query);
if($result)
{
    echo "went trough";
    return 1;
}
else
{
    echo mysql_error();//return 0;*/
}

?>
