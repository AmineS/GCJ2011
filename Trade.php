<?php
include_once('dbConnect.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Trade
 *
 * @author Omega
 */
class Trade {
   public $time_stamp;
   public $buy_ref;
   public $sell_ref;
   public $price;
   public $amount;

   public function __construct($buy_ref, $sell_ref, $price, $amount){
       $this->buy_ref = $buy_ref;
       $this->sell_ref = $sell_ref;
       $this->price = $price;
       $this->amount = $amount;
   }

   public function  __destruct() {
        ;
    }

    public function insertTradeBook(){
        $q = "INSERT INTO trade_book (`timestamp`, `buy_ref`, `sell_ref`, `price`, `amount`)
            values (NOW(), '$this->buy_ref', '$this->sell_ref', '$this->price', '$this->amount')";
               $query = mysql_query($q);
        if($query){
            return 1;
        }
        else echo mysql_error();
    }

    public function toString(){
        return $this->buy_ref."  ".$this->sell_ref."  ".$this->price."  ".$this->amount;
    }

}
$trade = new Trade(12, 13, 100, 12);
$trade->insertTradeBook();
echo $trade->toString();
unset($trade);
?>
