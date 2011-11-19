<?php

 include_once('dbConnect.php');
 include_once ('Order.php');

/* 
 * if message is not identified as an error, kill the 
 * process and return an error
 */
 

/*
 * need to generate an XML respons
 */
$msgType = $_POST["MessageType"];
if(strcmp($msgType, "O")!=0 )
{
    die('Message did not identify as an order!');
}

/*
 * Load data from http post request
 */
$from = $_POST["From"];
$bs = $_POST["BS"];
$shares = $_POST["Shares"];
$stock = $_POST["Stock"];
$price= $_POST["Price"];
$twilio= $_POST["Twilio"];
$state = 'U';

if($twilio=='N'){
    $twilio=0;
}
else{
    $twilio=1;
}
/*
 * Create a new order
 */

$order =new Order($from, $bs, $shares, $stock, $price, $twilio, $state);
$order->isValid();
$order->insertPending();


//$q="INSERT INTO order_book_pending (`from`, `bs`, `shares`, `stock`, `price`, `twilio`, `timestamp`,`state`)
//	VALUES ('$from', '$bs', '$shares', '$stock', '$price', '$twilio', CURRENT_TIMESTAMP, 'U');";

//$fh = fopen("wtf.txt","w+");
//fwrite($fh, $q1);
//fclose($fh);

//$query = mysql_query($q);
//include('processor.php');
//processTransactions();

//mysql_close();
?>
