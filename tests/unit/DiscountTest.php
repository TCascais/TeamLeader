<?php


class DiscountTest extends \PHPUnit\Framework\TestCase
{
    public function test_that_customer_is_set()
    {
        $customers = new App\Classes\Customers;
        $customers->populateCustomers(".\app\data\customers.json");
        $customersArray = $customers->getCustomers();

      
        $this->assertNotEmpty($customersArray);
    }
    
    public function test_that_customer_with_revenue_over_1000_can_be_retrieved()
    {
        $customers = new App\Classes\Customers;
        $customers->populateCustomers(".\app\data\customers.json");
        $customersArray = $customers->getCustomers();

        $customersWithRevenue = $customers->getCustomersByMinRevenue('1000');

        foreach ($customersWithRevenue as $customer) 
        {
            $this->assertGreaterThan('1000', $customer['revenue']);
        }

    }

    public function test_that_customer_with_id_1_can_be_retrieved()
    {
        $customers = new App\Classes\Customers;
        $customers->populateCustomers("./app/data/customers.json");
        $customersArray = $customers->getCustomers();

        $customerWithId = $customers->getCustomerById('1');

        $this->assertNotEmpty($customerWithId);
    }


    public function test_that_products_is_set()
    {
        $products = new App\Classes\Products;
        $products->populateProducts("./app/data/products.json");
        $productsArray = $products->getProducts();   

        $this->assertNotEmpty($productsArray);
    }

    public function test_that_product_with_id_a101_can_be_retrieved()
    {
        $products = new App\Classes\Products;
        $products->populateProducts("./app/data/products.json");
        $productWithId = $products->getProductById('A101');   

        $this->assertNotEmpty($productWithId);   
    }

    public function test_that_order_is_set()
    {
        $orders = new App\Classes\Orders;
        $orders->populateOrders('./app/example-orders');
        $ordersArray = $orders->getOrders();

        $this->assertNotEmpty($ordersArray);

    }

    public function test_that_customer_discount_is_set()
    {

        $customersObj = new App\Classes\Customers;
        $ordersObj = new App\Classes\Orders;
        $discountsObj = new App\Classes\Discounts;

        $customersObj->populateCustomers("./app/data/customers.json");
        $ordersObj->populateOrders('./app/example-orders');

        $orders = $ordersObj->getOrders();

        $discountsObj->setOrder($orders[1]);
        $discountsObj->calcCustomerRevenueDiscount('1000', $customersObj);

        $result = $discountsObj->getCalculatedOrder();

        $this->assertArrayHasKey('customerDiscount',$result);

    }

    public function test_that_discount_20_Percent_is_given_on_Cheapest_product_by_category()
    {
        $ordersObj = new App\Classes\Orders;
        $discountsObj = new App\Classes\Discounts;
        $productsObj = new App\Classes\Products;

        $ordersObj->populateOrders('./app/example-orders');
        $productsObj->populateProducts("./app/data/products.json");

        $orders = $ordersObj->getOrders();

        $discountsObj->setOrder($orders[2]);
        $discountsObj->calcProductDiscountByCategory('1', '2',$productsObj);
        
        $result=$discountsObj->getCalculatedOrder();

        $this->assertArrayHasKey('categoryDiscount',$result);
    }

    public function test_that_discount_Free_Items_is_given()
    {
        $ordersObj = new App\Classes\Orders;
        $discountsObj = new App\Classes\Discounts;
        $productsObj = new App\Classes\Products;

        $ordersObj->populateOrders('./app/example-orders');
        $productsObj->populateProducts("./app/data/products.json");

        $orders = $ordersObj->getOrders();

        $discountsObj->setOrder($orders[1]);
        $discountsObj->calcFreeProductDiscount('2', '5',$productsObj);
        
        $result=$discountsObj->getCalculatedOrder();

        $this->assertArrayHasKey('free',$result);   
    }

    public function test_that_all_discounts_are_given()
    {
        $ordersObj = new App\Classes\Orders;
        $discountsObj = new App\Classes\Discounts;
        $productsObj = new App\Classes\Products;
        $customersObj = new App\Classes\Customers;

        $ordersObj->populateOrders('./app/example-orders');
        $productsObj->populateProducts("./app/data/products.json");
        $customersObj->populateCustomers("./app/data/customers.json");

        $orders = $ordersObj->getOrders();

        $discountsObj->setOrder($orders[3]);
        $discountsObj->calcCustomerRevenueDiscount('1000', $customersObj);
        $discountsObj->calcProductDiscountByCategory('1', '2',$productsObj);
        $discountsObj->calcFreeProductDiscount('2', '5',$productsObj);
        
        $result=$discountsObj->getCalculatedOrder();

        $this->assertArrayHasKey('customerDiscount',$result);
        $this->assertArrayHasKey('categoryDiscount',$result);   
        $this->assertArrayHasKey('free',$result);
    }

    public function test_that_discounted_JSON_files_are_created()
    {
        $ordersObj = new App\Classes\Orders;
        $discountsObj = new App\Classes\Discounts;
        $productsObj = new App\Classes\Products;
        $customersObj = new App\Classes\Customers;

        $ordersObj->populateOrders('./app/example-orders');
        $productsObj->populateProducts('./app/data/products.json');
        $customersObj->populateCustomers('./app/data/customers.json');

        $orders = $ordersObj->getOrders();

        foreach($orders as $order){

            $discountsObj->setOrder($order);
            $discountsObj->calcCustomerRevenueDiscount('1000', $customersObj);
            $discountsObj->calcProductDiscountByCategory('1', '2',$productsObj);
            $discountsObj->calcFreeProductDiscount('2', '5',$productsObj);
            
            $jsonFile = $discountsObj->createJsonFile('./app/discounted-orders/');

            $this->assertFileExists('./app/discounted-orders/'.$jsonFile);
        }
    }

}