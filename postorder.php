<?php
$postOrder = true; 

require_once 'StockExchangePackage.php';

/* 
 * if message is not identified as an error, kill the 
 * process and return an error
 */


/*
 * need to generate an XML respons
 */
$msgType = $_Post["MessageType"];
if(strcmp($msgType, "O")!=0 )
{
    die('Message did not identify as an order!');
}
/*
 * Load data from http post request
 */
$from = $_Post["From"];
$bs = $_Post["BS"];
$shares = $_Post["Shares"];
$stock = $_Post["Stock"];
$price= $_Post["Price"]; 
$twilio= $_Post["Twilio"];
$state = 'U';


/*
 * Create a new order
 */
$order =new Order($from, $bs, $shares, $stock, $price, $twilio, $state);
$order->isValid();
$order->insertToPending();

include('processor.php');
processTransactions();

//mysql_close();
?>
