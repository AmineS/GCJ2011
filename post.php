<?php

function postToBroker($brokerAddress, $brokerPort, $brokerEndPoint, $response)
{
            
    $url = 'http://' . $brokerAddress . ':'. $brokerPort .'/'. $brokerEndPoint;
    
    $r = new HttpRequest($url, HttpRequest::METH_POST);

    $r->addRawPostData($response);
    $r->setContentType("Content-Type: text/xml");

    try {
        $success = $r->send()->getBody();
    } catch (HttpException $ex) {
        echo $ex;
    }
    die($response);
}

?>
