
<html>

    <head>
        <title>Database</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

</html>



<?php
    $servername = "localhost";
$username = "ESP32";
$password = "esp32io.com";
$database_name = "vehicle_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!empty($_GET['search'])) {

                        $Search_Query = $conn->real_escape_string($_GET['search']); // Escape special characters in string if any
    $rowsperpage = 10;
    //$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
    //$startAt = $perPage * ($page - 1);
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM new_tbl WHERE make LIKE '%{$Search_Query}%' OR model LIKE '%{$Search_Query}%'  "));
    $totalpages = ceil($r['total'] / $rowsperpage);

    // get the current page or set a default
    if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
        // cast var as int
        $currentpage = (int) $_GET['currentpage'];
    } else {
        // default page num
        $currentpage = 1;
    } // end if

// the offset of the list, based on current page
    $offset = ($currentpage - 1) * $rowsperpage;
// if current page is greater than total pages...
    if ($currentpage > $totalpages) {
        // set current page to last page
        $currentpage = $totalpages;
    } // end if
// if current page is less than first page...
    if ($currentpage < 1) {
        // set current page to first page
        $currentpage = 1;
    } // end if
// the offset of the list, based on current page
    $offset = ($currentpage - 1) * $rowsperpage;

    $r = mysqli_query($conn, "SELECT * FROM new_tbl WHERE make LIKE '%{$Search_Query}%' OR model LIKE '%{$Search_Query}%' LIMIT $offset, $rowsperpage ");

    $range = 3;

// if not on page 1, don't show back links
    if ($currentpage > 1) {
        // show << link to go back to page 1
        echo " <a href='{$_SERVER['REQUEST_URI']}&currentpage=1'><<</a> "; //REQUEST_URI prints also query string attached to the url but REQUEST_URI dont.
        // get previous page num
        $prevpage = $currentpage - 1;
        // show < link to go back 
        echo " <a href='{$_SERVER['REQUEST_URI']}&currentpage=$prevpage'><</a> ";
                        } // end if

    for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
        // if it's a valid page number...
        if (($x > 0) && ($x <= $totalpages)) {
            // if we're on current page...
            if ($x == $currentpage) {
                // 'highlight' it but don't make a link
                echo " [<b>$x</b>] ";
                // if not current page...
            } else {
                // make it a link
                echo " <a href='{$_SERVER['REQUEST_URI']}&currentpage=$x'>$x</a> ";
            } // end else
        } // end if
    } // end for
    if ($currentpage != $totalpages) {
        // get next page
        $nextpage = $currentpage + 1;
        // echo forward link for next page
        echo " <a href='{$_SERVER['REQUEST_URI']}&currentpage=$nextpage'>></a> ";
                            // echo forward link for lastpage
        echo " <a href='{$_SERVER['REQUEST_URI']}&currentpage=$totalpages'>>></a> ";
       
    } // end if
     echo " <a href='http://localhost/PhpProject2/index.php'> <<<<</a> ";
    
    //displaying data in table rows
                        
    $html = "<table class='table table-bordered'>";

    $html .= "
    <tr class='bg-primary'>
      <th>ID </th>
      <th>Make</th>
      <th>Model</th>
      <th>Year</th>
      <th>Description</th>
    </tr>
 ";
     

    if (mysqli_num_rows($r) > 0) {
        while ($row = mysqli_fetch_assoc($r)) {
            $html .= "<tr><td>" . $row['id'] . "</td>";
            $html .= "<td>" . $row['make'] . "</td>";
            $html .= "<td>" . $row['model'] . "</td>";
            $html .= "<td>" . $row['year'] . "</td>";
            $html .= "<td>" . $row['description'] . "</td></tr>";
        }

        $html .= "</table>";
        echo $html;
        
        
    } else {
        echo "Sorry! No records  for this search found";
    }
} else {

}
$conn->close();

