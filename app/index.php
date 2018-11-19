<?php
namespace App;
require('../vendor/autoload.php');


/* import classes */
$decoderObj = new Classes\JsonDecoder;
$customersObj = new Classes\Customers;
$productsObj = new Classes\Products;
$ordersObj = new Classes\Orders;
$discountsObj = new Classes\Discounts;

/* define .json locations */
$customerJsonFile = 'data/customers.json';
$productJsonFile = 'data/products.json';
$ordersDir = 'example-orders';

/* populate objects Classes */
$customersObj->populateCustomers($customerJsonFile);
$productsObj->populateProducts($productJsonFile);
$ordersObj->populateOrders($ordersDir);

/* get all orders in one array */
$orders = $ordersObj->getOrders();

?>

<html>
<style>
table{
    border-style: solid;
    border-width: 5px;
}

table th{
    border-bottom-style: solid;
    border-bottom-width: 2px;
}
</style>
<head>
    <title>Original Orders</title>
</head>
<body>
    <?php if(!isset($_GET['calculated'])): ?>
        <h1>Orders:</h1>
        <p><a href="index.php?calculated">Calculate discounts</a></p>
        <?php /* For each order in orders create a new table */ ?>
        <?php foreach($orders as $order): ?>
            
            <?php /* Get the customer for the specific order */ ?>
            <?php $customer = $customersObj->getCustomerById($order['customer-id']); ?>

            <?php /* Set the order in the discounts class */ ?>
            <?php $discountsObj->setOrder($order); ?>
            
            <table width="600px">
                <tr align="left">
                    <th>Order:</th>
                    <th><?php echo $order['id'] ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr align="left">
                    <th>Customer:</th>
                    <th><?php echo $customer['name'] ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                </tr>
                <tr align="left">
                    <th>Item</th>
                    <th>Id</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                
                <?php /* foreach item in the order, echo it in a new table row. */ ?>
                <?php foreach ($order['items'] as $item): ?>

                    <?php /* get the product associated with the product id and show its properties. */ ?>
                    <?php $product = $productsObj->getProductById($item['product-id']); ?>
                    <tr>
                        <td width="40%"><?php echo $product['description']; ?></td>
                        <td width="20%"><?php echo $item['product-id']; ?></td>
                        <td width="12%"><?php echo $item['quantity']; ?></td>
                        <td width="10%"><?php echo $item['unit-price']; ?></td>
                        <td width="10%"><b><?php echo $item['total']; ?></b></td>    
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b><?php echo $order['total']; ?></b></td>
                </tr>
            </table>
            <p></p>
        <?php endforeach; ?>

    <?php elseif(isset($_GET['calculated'])): ?>
        <h1>Discounted Orders:</h1>
        <?php /* For each order in orders, calculate the discounts. */ ?>
        <?php foreach($orders as $order): ?>
            <?php /* Get the customer associated with the customer ID in the order */ ?>
            <?php $customer = $customersObj->getCustomerById($order['customer-id']); ?>

            <?php /* Populate the order in the discount class */ ?>
            <?php $discountsObj->setOrder($order); ?>

            <?php /* Calculate the Customer revenue discount. (Revenue, Customer) */ ?>
            <?php $discountsObj->calcCustomerRevenueDiscount("1000", $customersObj); ?>

            <?php /* Calculate the product by category discount. (Category-id, Quantity_For_Discount, Product) */ ?>
            <?php $discountsObj->calcProductDiscountByCategory('1', '2', $productsObj); ?>
            
            <?php /* Calculate the free product discount (Category-id, Quantity_For_Discount, Product) */ ?>
            <?php $discountsObj->calcFreeProductDiscount('2', '5', $productsObj); ?>
            
            <?php /* Create JSON file of discounted order. */ ?>
            <?php $discountsObj->createJsonFile('discounted-orders/'); ?>

            <?php /* Get the calculated order */ ?>
            <?php $order = $discountsObj->getCalculatedOrder(); ?>
            <table width="600px">
                <tr align="left">
                    <th>Order:</th>
                    <th><?php echo $order['id'] ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr align="left">
                    <th>Customer:</th>
                    <th><?php echo $customer['name'] ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                </tr>
                <tr align="left">
                    <th>Item</th>
                    <th>Id</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                
                <?php /* For each item in order, create a new table row and fill it with the properties. */ ?>
                <?php foreach ($order['items'] as $item): ?>
                <?php $product = $productsObj->getProductById($item['product-id']); ?>
                <tr>
                    <td width="40%"><?php echo $product['description']; ?></td>
                    <td width="20%"><?php echo $item['product-id']; ?></td>
                    <td width="12%"><?php echo $item['quantity']; ?></td>
                    <td width="10%"><?php echo $item['unit-price']; ?></td>
                    <td width="10%"><b><?php echo $item['total']; ?></b></td>    
                </tr>

                <?php endforeach; ?>
                <tr>
                    <td><b>Total</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b><?php echo $order['total']; ?></b></td>
                </tr>
                
                <?php /* If there are any discounts given, show the discounts. */ ?>
                <?php if(array_key_exists('free', $order) || array_key_exists('customerDiscount', $order) || array_key_exists('categoryDiscount', $order)): ?>
                <tr align="left">
                    <th>Discount:</th>
                    <th>Product ID:</th>
                    <th>Quantity:</th>
                    <th></th>
                    <th></th>
                </tr>
                    <?php /* If customerDiscount exists, show it. */ ?>
                    <?php if(array_key_exists('customerDiscount', $order)): ?>
                    <tr>
                        <td>Customer Revenue over 1000</td>
                        <td>-</td>
                        <td>10%</td>
                        <td></td>
                        <td>-<?php echo $order['customerDiscount']; ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php /* If CategoryDiscount exists, show each of the given discounts. */ ?>
                    <?php if(array_key_exists('categoryDiscount', $order)): ?>
                        <?php foreach($order['categoryDiscount'] as $categoryItem): ?>
                        <tr>
                            <td>2 or more Tools (Cheapest)</td>
                            <td><?php echo $categoryItem['product-id']; ?></td>
                            <td>10%</td>
                            <td></td>
                            <td>-<?php echo $categoryItem['discount']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php /* If free items are given based on the discount, show the items. */ ?>
                    <?php if(array_key_exists('free', $order)): ?>
                        <?php foreach($order['free'] as $freeItem): ?>
                        <tr>
                            <td>Free product (Buy 5, 1 free)</td>
                            <td><?php echo $freeItem['product-id']; ?></td>
                            <td><?php echo $freeItem['freeQuantity']; ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php endforeach;?>
                    <?php endif; ?>

                    <?php /* Show the remaining total after all discounts */ ?>
                    <tr>
                        <td><b>Total after discounts</b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b><?php echo $order['discountedTotal']; ?></b></td>
                <?php endif; ?>

            </table>
            <p></p>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
