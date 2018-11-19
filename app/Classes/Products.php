<?php

namespace App\Classes;

class Products
{
    public $products;

    
    /* Setters */
    public function populateProducts($jsonFile)
    {
        $decoderObj = new JsonDecoder;

        //Set the returned decoded array as $jsonString.
        $jsonString = $decoderObj->decodeJson($jsonFile);

        //Set 
        $this->products = $jsonString;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function getProductById($id)
    {
        $productResult = null;

        foreach($this->products as $product)
        {
            if($product['id'] == $id)
            {
                $productResult = $product; 
            }
        }

        return $productResult;
    }

}