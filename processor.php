<?php
require_once('dbConnect.php');
require_once ('OrderBook.php');
require_once ('Order.php');
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
            $orderBook = new OrderBook();
            $orderBook->moveToActive(true, 10);
            $orderBook->setActiveIndex(0);
            //$orders = $orderBook->getActiveOrdersToMatch();

            $unfilledOrders = $orderBook->getActiveOrdersToMatch();

            //$unfilledOrders=array();
           
            //for ( $i=0; $i < $orderBook->getActiveIndex(); $i++){
                //$unfilledOrders[$i]=$orders[$i];
                
            }
            
            for( $j=$orderBook->getActiveIndex(); $j< count($unfilledOrders); $j++){
                $newOrderInProcess=$unfilledOrders[$j];

                  
                
                
                $tradeprice=0;
                $tradeShares=0;
                $buyer=-1;
                $seller=-1;
                $tradeStock= $newOrderInProcess->getStock();
                $index=0;
                
                
                // main algorithm 
                   
        
               //get matching list
                $matchingList = array();
                
                if($newOrderInProcess->getBS()=="S"){
                    $tradeprice= $newOrderInProcess->getPrice();
                    $seller= $newOrderInProcess->getId();
                    for($m=0;$m<count($unfilledOrders); $m++){
                        if($unfilledOrders[$m]->getBS()=="B" && $unfilledOrders[$m]->getStock()==$tradeStock && 
                                $unfilledOrders[$m]->getPrice() >= $tradeprice)
                            array_push ($matchingList, $m);
                       
                    }
                     
                //$unfilledList= get records from database such that: 
                //recods.bs="b" & records.stock= $stock & records.price>=$neworder->price;
                
                }else{
                    $buyer= $newOrderInProcess->getId();
                
                //$list= get records from database such that: 
                //recods.bs="b" & recods.stock= $stock & records.price<=$neworder->price;
                    
                    for($m=0;$m<$j; $m++){                              ///////
                        if($unfilledOrders[$m]->getBS()=="S" && $unfilledOrders[$m]->getStock()==$tradeStock && 
                                $unfilledOrders[$m]->getPrice() <= $tradeprice)
                            array_push ($matchingList, $m);
                        
                    }
                }

                print_r($matchingList);

                if(count($matchingList)==0){
                                                        /////
                    echo "<br/> BREAK ". $j . " *******";
                     
                     continue;
                }
                   

                    echo "<br> Unfilled orders before while <br/>";
                    forEach($unfilledOrders as $var){
                       echo $var->toString()."--".$var->getId();
                       echo "<br/>";
                    }
                
                echo "<br/> Cur ORDER before loop--------------------------<br/>";
                    echo  $newOrderInProcess->toString()."--".$newOrderInProcess->getId();
                    echo "<br/>**************<br/>";
                    
                $residualOrder = newResidual($newOrderInProcess);

                //echo "<br> Unfilled orders before while <br/>";
                  //  forEach($unfilledOrders as $var){
                   //    echo $var->toString()."--".$var->getId();
                  //     echo "<br/>";
                
                while(($match=getmatch($unfilledOrders ,$matchingList))!=-1 && $residualOrder->getShares()>0){


                    echo "<br/>**********loop****<br/>";
                    
                    echo count($unfilledOrders)."<br/>";
                    forEach($unfilledOrders as $var){
                       echo $var->toString()."+++++";
                       echo "<br/>";
                    }
                    
                    $matchResidual= newResidual($unfilledOrders[$match]);
                     
                     
                    echo "Matching ORDER before set bs<br/>";
                    echo  $unfilledOrders[$match]->toString()."--".$unfilledOrders[$match]->getId();
                    echo "<br/>**************<br/>";
                    
                    $newOrderInProcess->setBS('f');
                    
                    $unfilledOrders[$match]->setBS('f');
                    
                    echo "Matching ORDER<br/>";
                    echo  $unfilledOrders[$match]->toString()."--".$unfilledOrders[$match]->getId();
                    echo "<br/>**************<br/>";
                    
                    
                   
                    
                    $tradeShares = min($unfilledOrders[$match]->getShares(), $residualOrder->getShares() );
                    $matchResidual->setShares( $unfilledOrders[$match]->getShares() - $tradeShares);
                    if($matchResidual->getBS()=="S") 
                        $seller=($unfilledOrders[$match]->getParent()==0 )?$unfilledOrders[$match]->getId():$unfilledOrders[$match]->getParent();
                    if($matchResidual->getBS()=="B") 
                        $buyer=($unfilledOrders[$match]->getParent()==0 )?$unfilledOrders[$match]->getId():$unfilledOrders[$match]->getParent();
                    
                    $residualOrder->setShares($residualOrder->getShares() - $tradeShares);
                    
                    $matchResidual->setShares( $unfilledOrders[$match]->getShares() - $tradeShares);
                    
                    if($tradeShares!=$unfilledOrders[$match]->getShares()){
                        
                        
                        
                        //add($matchResidual )to unfilled;       
                        array_push($unfilledOrders, $matchResidual);
                        echo "Res Matching ORDER<br/>";
                        echo  $matchResidual->toString()."--".$matchResidual->getId();
                        echo "<br/>**************<br/>";
                        
                    }
                    
                     
                    
                    echo "RESIDUAL ORDER<br/>";
                    echo  $residualOrder->toString()."--".$residualOrder->getId();
                    echo "<br/>**************<br/>";
                    
                    
                     echo "ORDER done<br/>";
                    echo  $newOrderInProcess->toString()."--".$newOrderInProcess->getId();
                    echo "<br/>*******END*******<br/>";
                    
                    //addrecord($matchNumber,$tradeStock, $tradeShares, $tradeprice, $buyer,$seller, current server time);
                   $query="INSERT INTO trade_book (`timestamp`,`stock`,`buy_ref`, `sell_ref`, `price`, `amount`)
                           values (NOW(),'$tradeStock','$buyer', '$seller', '$tradeprice', '$tradeShares' );";
                   echo "<br/>!!!!".$buyer." ".$seller." ".$tradeprice." ".$tradeShares." ".$tradeStock."!!!<br/>";
                   $response=mysql_query($query);
                   if($response){
                        echo "went trough";
                   }else{
                       echo "did not go through<br/>";
                   }
                    
                    
                    //construct new tradeRecord object + add it to tradebook 
                 }
                 if ( $residualOrder->getShares()>0){
                     array_push($unfilledOrders, $residualOrder);
                 }
                 //if($newOrderInProcess->getBS()!='f'){
                  //   array_push($unfilledOrders, $residualOrder);
                 //}
                
                    echo "<br> Unfilled ordera after while <br/>";
                    forEach($unfilledOrders as $var){
                       echo $var->toString()."--".$var->getId();
                       echo "<br/>";
                    }
            }
              $query2="TRUNCATE TABLE order_book_active;";
               $response2=mysql_query($query2);
               if($response2){
                    echo "Freshness";
               }else{
                   echo "Problem<br/>";
               }
            foreach ($unfilledOrders as $var){
                if($var->getBS()=='f'){
                    $var->insertArchive();
                }
                 else {
                     echo "<br/>**********".$var->toString();
                    $var->insertActive();
                }
            }
            releaseProcessor();

        }            
    //}
}

 function newResidual($newOrderInProcess){  // TEST OK 05:11

    $residualOrder= new Order($newOrderInProcess->getFrom(), $newOrderInProcess->getBS(), $newOrderInProcess->getShares(),
                    $newOrderInProcess->getStock(), $newOrderInProcess->getPrice(), $newOrderInProcess->getTwilio(), $newOrderInProcess->getState());
    $residualOrder->setParent($newOrderInProcess->getId());
    $residualOrder->setId( $newOrderInProcess->getId()+1);

    return $residualOrder;
}



        //this function returns 0 if the first argument has a better price (depending on whether 
        // he is a seller or buyer) than order 2; 1 otherwise
        function bestPrice($order1,$order2){
            if($order1->getBS() == "B")
                return ( ($order1->getPrice() > $order2->getPrice()) ? 0 : 1);
            else
                return ( ($order1->getPrice() < $order2->getPrice()) ? 0 : 1);
        }
        
        
        // this function gets the matching order with highest priority
        function getmatch($unfilledList ,$matchingList){
            $match=-1;
            $i=0;
            for($i=0; $i<count($matchingList); $i++){
                //echo "<br/>Time stamp**<br/> ";
                //echo "Temp ".$unfilledList[$matchingList[$i]]->getTimestamp()."**Match ".$unfilledList[1]->getTimestamp()."<br/>";
                if($unfilledList[$matchingList[$i]]->getBS()!='f')
                    if($match==-1 || bestPrice($unfilledList[$match],$unfilledList[$matchingList[$i]] ) ||
                        ($unfilledList[$match]->getPrice()==$unfilledList[$matchingList[$i]]->getPrice() 
                            && ($unfilledList[$match]->getTimestamp() > $unfilledList[$matchingList[$i]]->getTimestamp())))

                                $match = $matchingList[$i];
                                // time attribute shouls hold parent time
            }
            return $match;
        }



                
function lockProcessor()
{
    $query = "SELECT GET_LOCK('Singleton',5)";
    $result = mysql_query($query);
    
    $check = mysql_fetch_row($result);
    
    return $check[0];
}


function releaseProcessor()
{
    $query = "SELECT RELEASE_LOCK('Singleton')";
    $result = mysql_query($query);

    $check = mysql_fetch_row($result);

    return $check[0];
}
function processorIsRunning()
{
    
    $query = "SELECT IS_FREE_LOCK('Singleton')";
    $result = mysql_query($query);
    
    $check = mysql_fetch_row($result);
    
    return $check[0];    
}
?>
