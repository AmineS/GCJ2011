<?php
include_once('dbConnect.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of order
 *
 * @author Omega
 */
class Order {
    public $id;
    public $from;
    public $bs;
    public $shares;
    public $stock;
    public $price;
    public $twilio;
    public $timestamp;
    public $parent;
    public $state;
    public $has_child;

    public function __construct($from, $bs, $shares, $stock, $price, $twilio, $state){
        $this->from = $from;
        $this->bs = $bs;
        $this->shares = $shares;
        $this->stock = $stock;
        $this->price = $price;
        $this->twilio = $twilio;
        $this->state = $state;
    }

    public function  __destruct() {
        ;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId($id){
        return $this->id;
    }

    public function setParent($parent){
        $this->parent = $parent;
    }

    public function getParent(){
        return $this->parent;
    }

    public function setHasChild($has_child){
        $this->has_child = $has_child;
    }

    public function getHasChild(){
        return $this->has_child;
    }

    //isValid() checks that the fields meet the requirements
    public function insertIntoPending(){
        $q="INSERT INTO order_book_pending (`from`, `bs`, `shares`, `stock`, `price`, `twilio`, `timestamp`,`state`)
	VALUES ('$this->from', '$this->bs', '$this->shares', '$this->stock', '$this->price', '$this->twilio', CURRENT_TIMESTAMP, 'U')";
        $query = mysql_query($q);
        if($query){
            return 1;
        }
        else echo mysql_error();//return 0;
    }
    public function toString(){
        if($this->twilio==true) $twilioTemp="true";
        else $twilioTemp="false";
        return $this->from."  ".$this->bs."  ".$this->shares."  ".$this->price."  ".$twilioTemp."  ".$this->state;
    }

}
/*$order = new Order('from1', 'bs1', 100, 'stock1', 10, false, 'U');
$order->setId(12);
$order->setParent(13);
var_dump($order);
$order->insertIntoPending();
unset($order);
var_dump($order);*/
/*$what = fopen("./test2.txt", 'w');
fwrite($what,"fuck me");
fclose();*/
?>
