<?php

 include_once('dbConnect.php');
 include_once ('Order.php');
 include_once('OrderBook.php');
 include_once('post.php');
 include_once('processor.php');

/* 
 * if message is not identified as an error, kill the 
 * process and return an error
 */
 

/*
 * need to generate an XML respons
 */
/*$msgType = $_POST["MessageType"];
if(strcmp($msgType, "O")!=0 )
{
    $response = Order::generateRejectResponse("M");
    $r = new HttpRequest('http://localhost:40000/broker/endpoint', HttpRequest::METH_POST);
    $r->addPostFields(array('xml' => $response));
    $r->setContentType("Content-Type: text/xml");

    try {
         echo "<br/>";
        echo $r->send()->getBody();
           
    } catch (HttpException $ex) {

        echo $ex;
    }
    die($response);

    
}*/

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
$brokerAddress =$_POST["BrokerAddress"];
$brokerPort = $_POST["BrokerPort"];
$brokerEndPoint =$_POST["BrokerEndpoint"];

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
$result = $order->insertPending();
processTransactions();
$result=1;
//$fh = fopen("wow.txt", "a");
//fwrite($fh, $brokerAddress."   ".$brokerEndPoint."   ".$brokerPort);
//fclose($fh);
if($result == 1)
{    $aresponse = '<?xml version=”1.0” encoding=”UTF-8”?>\n <Response>\n

            <Exchange><Accept OrderRefId = "POL345" /></Exchange>\n </Response>\n';
    ////$order->generateAcceptResponse();
    echo $aresponse;
    echo "<br/>";
    echo "RESPONSE generated: ".$aresponse."<br/>";
    $brokerAddress="127.0.0.1";
    $brokerEndPoint="/broker";
    $brokerEndPoint="40000127.0.0.1";
    postToBroker($brokerAddress, $brokerPort, $brokerEndPoint, $aresponse);
}
else
{
    
    Order::generateRejectResponse('invalid dataset');
}

?>
