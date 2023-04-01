<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <body>
        <?php

        function post_or_get($index, $description) {
            if (isset($_POST[$index])) {
                return $_POST[$index];
            } else if (isset($_GET[$index])) {
                return $_GET[$index];
            } else {
                return null;
            }
        }

        $scriptName = "PutBidAuth.php";
        include("PHPprinter.php");
        $startTime = getMicroTime();

        $itemId = post_or_get('itemId');
        if ($itemId == null) {
            $itemId = $_GET['itemId'];
            if ($itemId == null) {
                printError($scriptName, $startTime, "Authentification for bidding", "You must provide an item identifier!<br>");
                exit();
            }
        }

        printHTMLheader("RUBiS: User authentification for bidding");
        include("put_bid_auth_header.html");
        print("<input type=hidden name=\"itemId\" value=\"$itemId\">");
        include("auth_footer.html");

        printHTMLfooter($scriptName, $startTime);
        ?>
    </body>
</html>
