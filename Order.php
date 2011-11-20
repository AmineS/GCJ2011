<?php
//include_once('dbConnect.php');
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
    private $id;
    private $from;
    private $bs;
    private $shares;
    private $stock;
    private $price;
    private $twilio;
    private $timestamp;
    private $parent;
    private $state;
    private $has_child;

    /*
     * Constructor for order object
     */
    public function __construct($from, $bs, $shares, $stock, $price, $twilio, $state){
        $this->from = $from;
        $this->bs = $bs;
        $this->shares = $shares;
        $this->stock = $stock;
        $this->price = $price;
        $this->twilio = $twilio;
        $this->state = $state;
        $this->parent = NULL;
    }


    /*
     * destructor
     */
    public function  __destruct() {
        ;
    }

    /*
     * getters and setters for all the variables and the id
     */
    public function getFrom(){
        return $this->from;
    }

    public function getShares(){
        return $this->shares;
    }

    public function setShares($shares){
        $this->shares=$shares;
    }

    public function getBS(){
        return $this->bs;
    }

    public function setBS($bs){
        $this->bs=$bs;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getStock(){
        return $this->stock;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setParent($parent){
        $this->parent = $parent;
    }

    public function getParent(){
        return $this->parent;
    }

    public function setTimestamp($timestamp){
        $this->timestamp = $timestamp;
    }

    public function getTimestamp(){
        return $this->timestamp;
    }    
    
    public function setHasChild($has_child){
        $this->has_child = $has_child;
    }

    public function getHasChild(){
        return $this->has_child;
    }

    /**
     *loads an order into the pending table of the database
     * @return type 
     */
    public function insertPending(){
        $q="INSERT INTO order_book_pending (`from`, `bs`, `shares`, `stock`, `price`, `twilio`, `timestamp`,`state`)
	VALUES ('$this->from', '$this->bs', '$this->shares', '$this->stock', '$this->price', '$this->twilio', NOW(), 'U');";

        
        $query = mysql_query($q);
        if($query){
            $this->id = mysql_insert_id();
            return 1;
        }
        else
        {
            return 0;
        }
    }

    public function insertActive(){
        $q="INSERT INTO order_book_active (`id`,`from`, `bs`, `shares`, `stock`, `price`, `twilio`, `timestamp`,`state`,`parent`, `has_child`)
	VALUES ('$this->id', '$this->from', '$this->bs', '$this->shares', '$this->stock', '$this->price', '$this->twilio', 
                '$this->timestamp', '$this->state','$this->parent', '$this->has_child');";
//
//        $fh = fopen("wtf.txt", "a");
//        fwrite($fh, $q);
//        fclose($fh);
        $query = mysql_query($q);
        if($query){
            return 1;
        }
        else echo mysql_error();//return 0;
    }
    
    public function insertArchive()
    {
        $q="INSERT INTO order_book_archive (`id`,`from`, `bs`, `shares`, `stock`, `price`, `twilio`, `timestamp`,`state`,`parent`, `has_child`)
            VALUES ('$this->id', '$this->from', '$this->bs', '$this->shares', '$this->stock', '$this->price', '$this->twilio', 
                '$this->timestamp', '$this->state', '$this->parent', '$this->has_child');";
        
                $query = mysql_query($q);
        if($query){
            echo "went trough";
            return 1;
        }
        else echo mysql_error();//return 0;
    }
    
    public function removeFromPending()
    {
        $q = "DELETE FROM order_book_pending WHERE id=$this->id";
        $query = mysql_query($q);
    }
    
    public function removeFromActive()
    {
        $q = "DELETE FROM order_book_active WHERE id=$this->id";
        $query = mysql_query($q);
    }
    public function toString(){
        if($this->twilio==true) $twilioTemp="true";
        else $twilioTemp="false";
        return $this->from."  ".$this->bs."  ".$this->shares."  ".$this->price."  ".$twilioTemp."  ".$this->state;
    }
    
    public function isValid()
    {
        return true;
    }

    public static function generateResponse($response)
    {
        $brokerResponse = '<?xml version="1.0" encoding="UTF-8"?>\n <Response>\n
            <Exchange>';
        $brokerResponse .= $response;
        $brokerResponse .= '</Exchange>\n </Response>\n';
                
        return $brokerResponse;
    }

    public static function generateRejectResponse($reason)
    {
        $response = '<Reject Reason="';
        $response .= "$reason";
        $response .= '" />';
        echo "$response";

        return Order::generateResponse($response);
    }

    public function generateAcceptResponse()
    {
        $response = '<Accept OrderRefId="';
        $response .= $this->bs . ''. $this->id;
        $response .= '" />';

        return generateResponse($response);
    }
}
?>
