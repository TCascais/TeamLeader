# TeamLeader Discount Assignment
This is the result for the discounts assignment created by Teamleader. </br>
The code is commented, for more details I suggest looking at the code. <br><br>

For a quick view on the classes, see classdiagram.jpeg </br>

## Folder Structure
The assignment consists of the following folder structure: </br></br>

App\Classes 		: Contains the classes to perform the discounts. </br>
App\data		: Contains the Customers and Products JSON files. </br>
App\discounted-orders	: The folder in which the new discounted orders are placed. </br>
App\example-orders	: Contains the example orders provided by Teamleader. </br></br>

Tests\			: Contains the unit tests. </br></br>

Vendor\ 		: Contains all the composer package files for PHP unit. </br>

## Getting started
The App folder containts an index.php. This file was created to show the start and the endresult. </br>
By clicking on the "Calculate discount" link, it will generate the discounted orders files. 
It will also show the discounted orders in a table format. </br></br>
(I didn't do much of formatting as I found it less important compared to the backend code) </br>


## Running the tests
PHPUnit has been used to perform the unit tests. To run the test, run the following command from within windows cmd:
```
$ vendor\bin\phpunit
```

### Tools used
Tools: </br>
- PHPUnit for unit testing
- Draw.io for the classdiagram
