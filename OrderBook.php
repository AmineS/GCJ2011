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
    private $activeIndex;
    private $activeId;
    
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

    public function getActiveOrdersToMatch(){
        $orders=array();
        $query = "Select * from order_book_active";
        $result = mysql_query($query);
        $i=0;
        while ($row = mysql_fetch_array($result))
        {
            $order = new Order($row[1], $row[2],$row[3], $row[4], $row[5], $row[6],$row[9]);
            $order->setId($row[0]);
            $date=strtotime($row[7]);
            $order->setTimestamp($date);
            $order->setParent($row[8]);
            $order->setHasChild($row[10]);
            if($order->getId()==$this->activeId)
            {
                $this->activeIndex = $i;
            }
            $orders[$i]=$order;
           // echo $orders[$i]->toString()."*****";
            $i=$i+1;
            
        }
        return $orders;
        
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
    public function moveToActive($resetPending, $shift)
    {
        if ($resetPending || !isset($this->pendingOrders))
        {
            $this->loadPending();
        }        

        $result = $this->pendingOrders;
        //$labels = mysql_fetch_array($result);       
        $i = 0;
        while ($row = mysql_fetch_array($result))
        {
            $order = new Order($row[1], $row[2],$row[3], $row[4], $row[5], $row[6],$row[9]);
            $order->setId($row[0]*$shift);
            $order->setTimestamp($row[7]);
            $order->setParent($row[8]);
            $order->setHasChild($row[10]);
            if ($i ==0)
            {
                $this->activeId = $order->getId();
            }
            $order->insertActive();
            if ($shift > 0)
            {
                $order->setId($order->getId()/10);
            }
            $order->removeFromPending();
            $i++;
        }                
    }
    
    public function moveToArchive($resetActive)
    {
        if ($resetActive || !isset($this->activeOrders))
        {
            $this->loadActive();
        }        

        $result = $this->activeOrders;
        //$labels = mysql_fetch_array($result);       

        while ($row = mysql_fetch_array($result))
        {
            if($row[9]=='F')
            {
                $order = new Order($row[1], $row[2],$row[3], $row[4], $row[5], $row[6],$row[9]);
                $order->setId($row[0]);
                $order->setTimestamp($row[7]);
                $order->setParent($row[8]);
                $order->setHasChild($row[10]);
                $order->insertArchive();
                $order->removeFromActive();   
            }            
        }                  
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

    public function setActiveIndex($activeIndex){
        $this->activeIndex=$activeIndex;
    }
    public function getActiveIndex(){
        return $this->activeIndex;
    }

    public function setActiveId($activeId){
        $this->activeId=$activeId;
    }
    public function getActiveId(){
        return $this->activeId;
    }
}


?>
