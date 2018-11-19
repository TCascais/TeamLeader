# TeamLeader Discount Assignment
This is the result for the assignment created by log.

## Folder Structure
The assignment consists of the following folder structure:
App\Classes 		: Contains the classes to perform the discounts.
App\data		: Contains the Customers and Products JSON files.
App\discounted-orders	: The folder in which the new discounted orders are placed.
App\example-orders	: Contains the example orders provided by Teamleader.

Tests\			: Contains the unit tests.

Vendor\ 		: Contains all the composer package files for PHP unit.

## Getting started
The App folder containts an index.php. This file was created to show the start and the endresult. 
By clicking on the "Calculate discount" link, it will generate the discounted orders files.
It will also show the discounted orders in a table format.
(I didn't do much of formatting as I found it less important compared to the backend code)


## Running the tests

PHPUnit has been used to perform the unit tests. To run the test, run the following command from within windows cmd:
```
$ vendor\bin\phpunit
```

### Tools used
The only tool that has been used was PHPUnit for the unit tests.
