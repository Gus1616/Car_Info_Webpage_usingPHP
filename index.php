<?php

include "header.php";


$servername = "localhost";
$username = "root";
$password = "";
$database = "classicmodels";


//connecting to the database https://www.w3schools.com/php/php_mysql_connect.asp
$conn = @new mysqli($servername, $username, $password, $database);


// Check connection
if ($conn->connect_error) {
// error handling    
  include "404error_connection.html" ;die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Here are all our wonderful products</h2><p>Look for a quantity of stock, sort the stock in descending or asscending order, and search by product line below</p><br>";
// query for all products
$sql = "SELECT * FROM classicmodels.products";
$result = $conn->query($sql) or die($conn->error);

// search bar for stock
echo "<input type='number' id='filterNumber' onkeyup='filterQuantity()' placeholder='Enter number to find stock less than..eg 500'>";

//make sure to press button more than once to get asscending descending
echo "<button onclick='sortTable()' id = 'sortbutton'>Sort Stock-Asscending/Descending</button>";

// search bar for product lines
echo "<input type='text' id='myInput' onkeyup='productlineFunction()' placeholder='Enter product line..eg Classic Cars or Vintage Cars'>";

echo "<table id='product-data'>";
    echo "<tr><th>Quantity in Stock</th><th>Product Name</th><th>Product Description</th><th>Product Code</th><th>MSRP</th><th>Product Line</th></tr>";
while ($row = $result->fetch_assoc() )
    {
        echo "<tr><td>" . $row["quantityInStock"] . "</td><td>" . $row["productName"] . "</td><td>" . $row["productDescription"] . "</td><td>" . $row["productCode"] . "</td><td>" . $row["MSRP"] . "</td><td>" . $row["productLine"] . "</td></tr>";




    }
 echo "</table>";

//js for product line search bar
//code adapted from https://www.w3schools.com/howto/howto_js_filter_table.asp
echo "
    <script>
    function productlineFunction() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById('myInput');
      filter = input.value.toUpperCase();
      table = document.getElementById('product-data');
      tr = table.getElementsByTagName('tr');
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName('td')[5];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = '';
          } else {
            tr[i].style.display = 'none';
          }
        }       
      }
    }
    </script>
    ";

//sort button for quantity in stock code adapted from https://www.w3schools.com/howto/howto_js_sort_table.asp
echo "
    <script>
        function sortTable() {
          var table, rows, switching, i, x, y, shouldSwitch, switchcount = 0, dir;
          table = document.getElementById('product-data');
          switching = true;
          dir = 'asc';
          while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
              shouldSwitch = false;
              x = rows[i].getElementsByTagName('td')[0];
              y = rows[i + 1].getElementsByTagName('td')[0];
              if (dir == 'asc') {
              if (Number(x.innerHTML) < Number(y.innerHTML)) {
                shouldSwitch = true;
                break;
              }
            } else if (dir == 'desc') {
                if (Number(x.innerHTML) > Number(y.innerHTML)) {
          shouldSwitch= true;
          break;
          }
      }
    }
            if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      switchcount ++;
    } else {
      if (switchcount == 0 && dir == 'asc') {
        dir = 'desc';
        switching = true;
      }
    }
  }
}
    </script>
    ";

//filter by quantity in stock, code adapted from https://www.w3schools.com/howto/howto_js_filter_table.asp
echo "

    <script>
       
         function filterQuantity() {
        
      var input, filter, table, tr, td, i, numValue;
      input = document.getElementById('filterNumber').value;
      table = document.getElementById('product-data');
      tr = table.getElementsByTagName('tr');
      for(var i =0; i<tr.length; i++)
      {
         td = tr[i].getElementsByTagName('td')[0];
         
         if(td)
         {
            numValue = td.textContent || td.innerHTML;
            if(+numValue <= +input )
            {
              tr[i].style.display='';
            
            }
            else {
            tr[i].style.display = 'none';
            }
            if(numValue.indexOf(input) > -1)
            {
               tr[i].style.display='';
            }
         
         }
      
      }

        
        
        }
    
    
    
    
    
    
    
    </script>







    ";
    
    
    
    
$conn->close();

include "footer.php";
?>
