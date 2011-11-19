<?php

//require_once('dbConnect.php');
include_once 'dbConnect.php';
include_once 'Order.php';
include_once('OrderBook.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function processTransactions ()
{
    // if the process isn't running
    if (true)//(processorIsRunning()==1)
    {
        //try to lock it
        if (true)//(lockProcessor() == 1)
        {

            $orderBook= new OrderBook();
            
            $orders= $orderBook->getActiveOrdersToMatch();

            $unfilledOrders=array();
            
            $orderBook->setActiveIndex(3);
             echo $orderBook->getActiveIndex();
            for ( $i=0; $i < $orderBook->getActiveIndex(); $i++){
                $unfilledOrders[$i]=$orders[$i];
            }
            $newOrders=array();
            for( $j=$orderBook->getActiveIndex(); $j< count($orders); $j++){
                $newOrderInProcess=$orders[$j-$orderBook->getActiveIndex()];
                

            }

        }            
    }
}

function lockProcessor()
{
    $query = "SELECT GET_LOCK('Singleton',5)";
    $result = mysql_query($query);
    
    $check = mysql_fetch_row($result);
    
    return $check[0];
}

function unlockProcessor()
{
     $query = "SELECT RELEASE_LOCK('Singleton')";
      $result = mysql_query($query);
}

function processorIsRunning()
{
    
    $query = "SELECT IS_FREE_LOCK('Singleton')";
    $result = mysql_query($query);
    
    $check = mysql_fetch_row($result);
    
    $fh = fopen('wow.txt', 'w+');
    fwrite($fh, $check[0]);
    fclose($fh);
    
    return $check[0];
    
}

processTransactions();
?>
