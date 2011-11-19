<?php
include_once 'dbConnect.php';
include_once 'Order.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of orderBook
 *
 * @author Omega
 */
class OrderBook 
{
    private $pendingOrders;
    private $activeOrders;
    private $archiveOrders;
    
    public function __construct()
    {
        $this->pendingOrders = null; 
        $this->activeOrders = null;
        $this->archiveOrders = null;
    }
    
    /*load all pending orders */
    public function loadPending()
    {
        $query = "Select * from order_book_pending";
        $this->pendingOrders = mysql_query($query);            
    }
    
    /*load all active orders*/
    public function loadActive()
    {
        $query = "Select * from order_book_active";
        
        $this->activeOrders = mysql_query($query);
    }

    /*load all archived orders */
    public function loadArchive()
    {
        $query = "Select * from order_book_archive";
        $this->archiveOrders = mysql_query($query);        
    }

    /* move from pending table to actively processing table
     * resetPending is a boolean that specifies whether or not 
     * to reload the pending orders table
     */
    public function moveToActive($resetPending)
    {
        if ($resetPending || !isset($this->pendingOrders))
        {
            $this->loadPending();
        }        

        $result = $this->pendingOrders;
        //$labels = mysql_fetch_array($result);       

        while ($row = mysql_fetch_array($result))
        {
            $order = new Order($row[1], $row[2],$row[3], $row[4], $row[5], $row[6],$row[9]);
            $order->setId($row[0]);
            $order->setTimestamp($row[9]);
            $order->setParent($row[8]);
            $order->setHasChild($row[10]);
            
            $order->insertActive();
            $order->removeFromPending();
        }                
    }
    
    public function moveToArchive()
    {
        
    }
    
    public function loadAll()
    {
        
    }
    
    public function snapshotJSON()
    {
        
    }
    
    public function getPendingOrders()
    {
        return $this->pendingOrders;
    }
    
    public function getActiveOrders()
    {
        return $this->activeOrders;
    }
    
    public function getArchiveOrders()
    {
        return $this->archiveOrders;
    }
}


$ob = new OrderBook();
$ob->moveToActive(true);
?>
