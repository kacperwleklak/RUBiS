<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <body>
        <?php
        $scriptName = "RegisterItem.php";
        include("PHPprinter.php");
        include("DBQueries.php");
        $startTime = getMicroTime();
        $DBQueries = new DBQueries();

        function post_or_get($index, $description) {
            if (isset($_POST[$index])) {
                return $_POST[$index];
            } else if (isset($_GET[$index])) {
                return $_GET[$index];
            } else {
                printError($scriptName, $startTime, "Register item", "You must provide a $description!<br>");
                exit();
            }
        }

        $userId = post_or_get('userId', 'user identifier');
        $categoryId = post_or_get('categoryId', 'category identifier');
        $name = post_or_get('name', 'item name');
        $initialPrice = post_or_get('initialPrice', 'initial price');
        $reservePrice = post_or_get('reservePrice', 'reserve price');
        $buyNow = post_or_get('buyNow', 'Buy Now');
        $duration = post_or_get('duration', 'duration');
        $qty = post_or_get('quantity', 'quantity');

        if (isset($_POST['description'])) {
            $description = $_POST['description'];
        } else if (isset($_GET['description'])) {
            $description = $_GET['description'];
        } else {
            $description = "No description";
        }

        $DBQueries = new DBQueries();
        // Add item to database
        $start = date("Y-m-d H:i:s");
        $end = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") + $duration, date("Y")));
        $DBQueries->insert_items($name, $description, $initialPrice, $qty, $reservePrice, $buyNow, $end, $userId, $categoryId);

        printHTMLheader("RUBiS: Selling $name");
        print("<center><h2>Your Item has been successfully registered.</h2></center><br>\n");
        print("<b>RUBiS has stored the following information about your item:</b><br><p>\n");
        print("<TABLE>\n");
        print("<TR><TD>Name<TD>$name\n");
        print("<TR><TD>Description<TD>$description\n");
        print("<TR><TD>Initial price<TD>$initialPrice\n");
        print("<TR><TD>ReservePrice<TD>$reservePrice\n");
        print("<TR><TD>Buy Now<TD>$buyNow\n");
        print("<TR><TD>Quantity<TD>$qty\n");
        print("<TR><TD>Duration<TD>$duration\n");
        print("</TABLE>\n");
        print("<br><b>The following information has been automatically generated by RUBiS:</b><br>\n");
        print("<TABLE>\n");
        print("<TR><TD>User id<TD>$userId\n");
        print("<TR><TD>Category id<TD>$categoryId\n");
        print("</TABLE>\n");

        $DBQueries = null;

        printHTMLfooter($scriptName, $startTime);
        ?>
    </body>
</html>
