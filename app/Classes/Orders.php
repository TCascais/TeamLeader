<?php

namespace App\Classes;

class Orders
{

    public $orders = [];


    public function populateOrders($ordersDir)
    {
        $decoder = new JsonDecoder;
        $allOrders = scandir($ordersDir);
        
        $ordersArray = [];

        foreach($allOrders as $orderFile)
        {
            if(strpos($orderFile, '.json') !== false)
            {
                $decodedOrder = $decoder->decodeJson('./' . $ordersDir . '/' . $orderFile);
                array_push($ordersArray, $decodedOrder);
            }
            
        }
    
        $this->orders = $ordersArray;

    }

    public function getOrders(){
        return $this->orders;
    }

}