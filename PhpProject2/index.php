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


        <style>

            table

            {
                position:absolute;

                left:0.01%;

                border-style:solid;

                border-width:10px;

                border-color:pink;

            }

            text

            {
                position:absolute;

                left:5%;

                top:2%;
                border-style:solid;

                border-width:10px;

                border-color:pink;

            }
        </style>

    </head>
    <body>

        <div class="row justify-content-center my-5  ">
            <div class="col-6 text-center">
                <div class="input-group mb-3">
                    <input type="text" id="search" autocomplete="off" class="form-control form-control-lg" placeholder="Search Cars Here">
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-5  ">
            <div class="col-8 text-center">
                <table class="table table-bordered" id="table">
                    <thead class="bg-primary">
                        <tr>
                            <th>Id</th>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    <div class="col-md-12 text-center">
                        <ul class="pagination pagination-lg pager" id="myPager"></ul>
                    </div>
                        <?php
                        require 'C:\xampp\htdocs\PhpProject1\db.inc.php';
                        $rowsperpage = 10;
//$page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;
//$startAt = $perPage * ($page - 1);
                        $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM new_tbl ORDER BY id ,make ")); //Fetches one row of data from the result set and returns it as an associative array.
                        $totalpages = ceil($r['total'] / $rowsperpage); //Round number(total inserts/rowspage)
                        
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

                        $r = mysqli_query($conn, "SELECT * FROM new_tbl ORDER BY id ,make LIMIT $offset, $rowsperpage ");

                        $range = 3;

// if not on page 1, don't show back links
                        if ($currentpage > 1) {
                            // show << link to go back to page 1
                            echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
                            // get previous page num
                            $prevpage = $currentpage - 1;
                            // show < link to go back 
                            echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
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
                                    // make it a link and print all pages
                                    echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
                                } // end else
                            } // end if
                        } // end for
                        if ($currentpage != $totalpages) {
                            // get next page
                            $nextpage = $currentpage + 1;
                            // echo forward link for next page
                            echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
                            // echo forward link for lastpage
                            echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
                        } // end if
                       
                        if (mysqli_num_rows($r) > 0) {
                            while ($Row = mysqli_fetch_assoc($r)) {
                                //displaying data in table rows dynamically
                                ?>
                            <tr>
                                <td><?php
                                    echo $Row['id'];
                                    ?></td>
                                <td><?php
                                    echo $Row['make'];
                                    ?></td>
                                <td><?php
                                    echo $Row['model'];
                                    ?></td>
                                <td><?php
                                    echo $Row['year'];
                                    ?></td>
                                <td><?php
                                    echo $Row['description'];
                                    ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
                </table>
            </div>
        </div>
        <script>
            //triggers when keybord focus is removed from the search element
            $("#search").blur('change keyup paste mouseup', function () {
                $("#table").trigger("reset");   //reset the table
                var search_query = $(this).val();
                if (search_query != '') { //call ajax query if we have search word  or go to the homepage if search box is empty
                    $.ajax({
                        url: "LiveSearch.inc.php",
                        type: "GET",
                        data: {
                            search: search_query
                        },
                        success: function ($data) {
                            $("#table").html($data);
                        }
                    });
                } else {
                    window.location.href = "index.php"
                }
            });

        </script>

    </body>


</html>