<?php
namespace App\Classes;

class Customers
{

    public $customers;

    
    /* Setters */
    public function populateCustomers($jsonFile)
    {
        /* Define decoderObj as a new JsonDecoder. */
        $decoderObj = new JsonDecoder;

        /* Set the returned decoded array as $jsonString. */
        $jsonString = $decoderObj->decodeJson($jsonFile);

        /* Set all Customers */
        $this->customers = $jsonString;
    }

    /* Getters */
    public function getCustomers()
    {
        /* return all Customers */
        return $this->customers;
    }

    /* Get all the customers that have a revenue equal or above Revenue */
    public function getCustomersByMinRevenue($revenue){
        
        /* Define the $customersByRevenue array */
        $customersByRevenue = [];

        /* For each customer in the Customers array, check if the revenue is above $revenue. */
        foreach($this->customers as $customer)
        {
            /* If revenue of the customer is equal or more than $revenue, push the customer to the $customersByRevenue array. */
            if($customer['revenue'] >= '1000')
            {
                array_push($customersByRevenue, $customer); 
            }
        }
        
        /* Returns the $customersByRevenueArray */
        return $customersByRevenue; 
    }

    /* Get the customer by id. */
    public function getCustomerById($id)
    {
        /* Define customerResult Variable. */
        $customerResult = null;

        /* For each customer in Customers.json, check if the ID matches the given ID.*/
        foreach($this->customers as $customer)
        {
            if($customer['id'] == $id)
            {
                $customerResult = $customer; 
            }
        }

        /* Return the result of Customer */
        return $customerResult;

    }
}

?>