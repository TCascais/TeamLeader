<?php 

namespace App\Classes;

class Discounts
{

    public $order;

    /* Sets the order to be calculated */
    public function setOrder($order){

        /* Set the order as the given $order object. */
        $this->order = $order;

        /* Add a new key to the array which has the same value as the Total of the order. */
        $this->order['discountedTotal']=$this->order['total'];
    }

    /* Function to calculate the revenue discount. (Revenue_for_discount, Customer) */
    public function calcCustomerRevenueDiscount($revenue, Customers $customers)
    {   
        /* Call getCustomerById from the Customers class to get the customer associated with the id in the order. */
        $customer = $customers->getCustomerById($this->order['customer-id']);
        
        /* If the revenue is equal or more than $revenue, proceed. */
        if($customer['revenue'] >= $revenue)
        {
            /* Sets $discount to be 10% of the order Total. */
            $discount = $this->order['total'] * 0.10;

            /* Format $discount to show only 2 decimals. */
            $formattedDiscount = number_format($discount, 2);

            /* Create a new key in the Order array with the Formatted discount as value. */
            $this->order['customerDiscount'] = $formattedDiscount;

            /* Subtract the discount from the discountedTotal. */
            $this->order['discountedTotal'] = $this->order['discountedTotal'] - $this->order['customerDiscount'];
        }

    }

    /* Function to calculate the product discount for given category. (Category-id, Required_Quantity_for_discount, Products)*/
    public function calcProductDiscountByCategory($category_id, $discountQuantity,Products $products)
    {
        /* Define $productCategoryItems as empty array */
        $productCategoryItems = [];

        /* Define a counter for the foreach to create new unique keys in the array */
        $i = 0;

        /* For each items in order, proceed. */
        foreach($this->order['items'] as $item)
        {
            /* Call function getProductById from the Products Class to define a single $product */
            $product = $products->getProductById($item['product-id']);
            
            /*  If the product category is the same as the given category 
                AND the item Quantity is equal or greater than the Required amount for discount,
                Poplulate the $productCategoryItems array with the item properties. */
            if($product['category'] == $category_id && $item['quantity'] >= $discountQuantity)
            {
                $productCategoryItems[$i]['product-id'] = $item['product-id'];
                $productCategoryItems[$i]['quantity'] = $item['quantity'];
                $productCategoryItems[$i]['price'] = $item['unit-price'];
                $productCategoryItems[$i]['total'] = $item['total'];
            }
            $i++;
        }

        /* If $productCategoryItems array is not empty, proceed. */
        if(!empty($productCategoryItems))
        {
            /* Sort the array ascending by price. */
            array_multisort(array_column($productCategoryItems, "price"), SORT_ASC, $productCategoryItems);

            /* Calculate the discount. Which is 20% of the cheapest product's total. */
            $discount = $productCategoryItems[0]['total'] * 0.2;

            /* Format $discount to only have 2 decimals. */
            $formattedDiscount = number_format($discount, 2);

            /* Define a new array which only contains the product-id and discount of the item. */
            $discountArray = array("product-id" => $productCategoryItems[0]['product-id'], "discount" => $discount);

            /*  If "categoryDiscount" key does not exist in $order, create that key in $order filled with the $discount array.
                ELSE if it DOES exist, push $discountArray into 'categoryDiscount' key. */
            if(!array_key_exists("categoryDiscount", $this->order))
            {
                $this->order['categoryDiscount'] = array($discountArray);
            }else{
                array_push($this->order['categoryDiscount'], $discountArray);
            }

             /* Subtract the formattedDiscount from the discountedTotal. */
            $this->order['discountedTotal'] = $this->order['discountedTotal'] - $formattedDiscount;
        }
    }

    /* Function to calculate the free product discount. (CategoryID_For_Discount, Required_Quantity_For_Discount, Products) */
    public function calcFreeProductDiscount($category_id, $discountAmount,Products $products)
    {
        /* For each item in order, proceed. */
        foreach($this->order['items'] as $item)
        {
            /* Call function getProductById from the Products Class to define a single $product */
            $product = $products->getProductById($item['product-id']);

            /*  If the product category matches the given categoryID for discount
                AND the item quantity is equal or greater than Required_Quantity_For_Discount, proceed. */
            if($product['category'] == $category_id && $item['quantity'] >= $discountAmount)
            {
                /*  Free quantity is the bought items /  Required_Quantity_For_Discount. 
                    This is floored since it needs to be rounded down to a whole number. */
                $quantity = floor($item['quantity'] / $discountAmount);

                /* Define a new array which only contains the product-id and the free quantity of the item. */
                $discountArray = array("product-id" => $item['product-id'], "freeQuantity" => $quantity);

                /*  If "free" key does not exist in $order, create that key in $order filled with the $discount array.
                    ELSE if it DOES exist, push $discountArray into 'categoryDiscount' key. */
                if(!array_key_exists("free", $this->order))
                {
                    $this->order['free'] = array($discountArray);
                }else{
                    array_push($this->order['free'], $discountArray);
                }
            }
        }
    }

    /* Return the calculated order */
    public function getCalculatedOrder()
    {   
        return $this->order;
    }

    /* Create JSON file of discounted order */
    public function createJsonFile($pathToFolder)
    {
        $filename = 'discountedOrder' . $this->order['id'] . '.json';

        $fp = fopen($pathToFolder . $filename, 'w');
        fwrite($fp, json_encode($this->order, JSON_PRETTY_PRINT));
        fclose($fp);

        return $filename;
    }

}