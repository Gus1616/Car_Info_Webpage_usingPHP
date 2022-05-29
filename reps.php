<?php

include "header.php";


$servername = "localhost";
$username = "root";
$password = "";
$database = "classicmodels";

//create connection
$conn = @new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
//error handling 
  include "404error_connection.html"; die("Connection failed: " . $conn->connect_error);
}
echo "<h2>Information on sales reps across our company</h2><p>Click on an employee number for information about each sales rep regarding customer data!</p><br>";


// query for main table of sales rep including who they report to by name
$salesrepsql  = "SELECT t2.employeeNumber, CONCAT(t2.firstName, ' ', t2.lastName) As name, t2.email, CONCAT (t1.firstName, ' ', t1.lastName) AS reportsTo, offices.addressLine1,offices.addressLine2, offices.city, offices.state, offices.country
from employees t2
INNER JOIN employees t1 ON t1.employeeNumber = t2.reportsTo 
LEFT JOIN offices ON t2.officeCode = offices.officeCode
WHERE t2.jobTitle = 'Sales Rep';";



$employeeresult = $conn->query($salesrepsql) or die($conn->error);

//this is used to check if the id is empty or not
$employeeNumber = $_GET['id'] ?? null; 

echo "<table id='reps-data'>";
    echo "<tr><th>Rep Name</th><th>Email</th><th>Sales Manager</th><th>Address line 1</th><th>Adress Line 2</th><th>City</th><th>State</th><th>Country</th><th>Employee Number</th></tr>";
while ($row = $employeeresult->fetch_assoc() )
    {
        echo "<tr><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td><td>" . $row["reportsTo"] . "</td><td>" . $row["addressLine1"] . "</td><td>" . $row["addressLine2"] . "</td><td>" . $row["city"] . "</td><td>" . $row["state"]. "</td><td>" . $row["country"]. "</td>"  . "<td><a href='reps.php?id=".$row['employeeNumber']."'>". $row["employeeNumber"]. "</a></td></tr>";
    



    }
 echo "</table>";

if($employeeNumber != null)
{
//    query for looking at customer data including rounded payments from customer
    $customerinfo ="Select customers.customerNumber, customers.customerName, customers.addressLine1, customers.city, customers.country, customers.creditLimit, ROUND(SUM(amount),0) AS amount FROM customers  LEFT JOIN employees ON employees.employeeNumber = customers.salesRepEmployeeNumber 
        LEFT JOIN payments ON customers.customerNumber = payments.customerNumber WHERE employees.employeeNumber = '$employeeNumber'
        GROUP BY customers.customerNumber
        ";
   $customerresult = $conn->query($customerinfo) or die($conn->error);
        
        echo "<table id='customer-data'>";
//    total sales value for each rep
      echo"<caption id = 'val' style='text-align:left'></caption>";


                echo "<tr><th>Customer Number</th><th>Customer Name</th><th>Address</th><th>City</th><th>Country</th><th>Credit Limit</th><th>Total Payments from Customer</th></tr>";
            while ($row = $customerresult->fetch_assoc() )
                


    {
            echo "<tr><td>" . $row["customerNumber"] . "</td><td>" . $row["customerName"] . "</td><td>" . $row["addressLine1"] . "</td><td>" . $row["city"] . "</td><td>" . $row["country"] . "</td><td>" . $row["creditLimit"] . "</td><td>" . $row["amount"] . "</td></tr>";


            

    }

            echo "</table>";

    
    
}

//javascript for adding total sales value for each rep
echo "

    <script>
    var table = document.getElementById('customer-data'), sumVal = 0;
    for (var i = 1; i < table.rows.length; i++)
    {
        sumVal = sumVal + parseInt(table.rows[i].cells[6].innerHTML);
        
    }
    
    document.getElementById('val').innerHTML = 'Total Sales Value by the Rep: ' + sumVal;
    console.log(sumVal);
    
    
    
    
    
    </script>
    
    
    ";



        

$conn->close();





include "footer.php";










?>

