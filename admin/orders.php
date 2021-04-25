<?php
defined('IERG4210ADMIN') or define('IERG4210ADMIN', true);
require '../inc/config.inc.php';
if (auth_admin() == false) {
    header("Location: http://localhost");
    die();
}

$setting = 'categories';
function order_main()
{
    $db = DB();
    $sql = "SELECT * FROM orders";
    if ($orders = mysqli_query($db, $sql)) {

        $sql_product_list = "SELECT pid, name FROM products";
        $products = mysqli_query($db, $sql_product_list);
        $products_list = [];
        while ($row = mysqli_fetch_array($products)) {
            $products_list[$row["pid"]] = strip_tags($row["name"]);
        }

        $count = mysqli_num_rows($orders);
        require 'view/order_main.php';
        mysqli_free_result($orders);
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
    }
}

require 'view/header.php';

order_main();

require 'view/footer.php';