<?php
require_once('dbConnect.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function processTransactions ()
{
    // if the process isn't running
    if (processorIsRunning()==1)
    {
        //try to lock it
        if (lockProcessor() == 1)
        {

            
    
    
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
