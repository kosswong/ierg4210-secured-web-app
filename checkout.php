<?php
require 'inc/header.php';
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

try {
    $random_salt = bin2hex(random_bytes(8));
} catch (Exception $e) {
    $random_salt = bin2hex("IERG4210");
    die("Cannot generate random byte with random_bytes() function");
}

// Numeric check
$pid = (isset($_GET['pid']) && is_numeric($_GET['pid']) && ($_GET['pid'] > 0)) ? $_GET['pid'] : 1;

$cart = [
    0 => ["pid" => 0, "quality" => 5, "price" => 5],
    1 => ["pid" => 1, "quality" => 5, "price" => 5],
    2 => ["pid" => 2, "quality" => 5, "price" => 5],
];
$cart_sanitized = [];

foreach ($cart as &$item) {
    $sql = $conn->prepare('SELECT pid, name, price FROM products WHERE pid=? LIMIT 1');
    $sql->bind_param('i', $item["pid"]);
    $sql->execute();

    /*
     * fetch_array()：將讀出的資料同時以數字與欄位名稱各自存一次在陣列之中，相當於同一個值會出現兩次。
     * fetch_assoc()：將讀出的資料Key值設定為該欄位的欄位名稱。
     * fetch_row()：將讀出的資料Key值設定為依序下去的數字。
     *
     */

    if ($result = $sql->get_result()) {
        while ($row = $result->fetch_array()) {
            print_r($row);

            //$cart_sanitized[] = ;

        }
    }
}


// Server generates a digest that is composed of at least
$digest = [
    "currency" => "HKD",
    "bar" => "foo", //Merchant’s email address
    "salt" => $random_salt,
    "cart" => $cart,
    "total" => 150, //The total price of all selected products
];

?>
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" name="upload" value="1">
        <input type="hidden" name="business" value="sb-qawra5773820@business.example.com">
        <INPUT TYPE="hidden" name="charset" value="utf-8">
        <input type="hidden" name="item_name_1" value="Item Name 1">
        <input type="hidden" name="amount_1" value="1.00">
        <input type="hidden" name="shipping_1" value="1.75">
        <input type="hidden" name="item_name_2" value="Item Name 2">
        <input type="hidden" name="amount_2" value="2.00">
        <input type="hidden" name="shipping_2" value="2.50">
        <input type="submit" value="PayPal">
    </form>

<?php require 'inc/footer.php'; ?>