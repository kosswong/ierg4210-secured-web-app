<?php
require 'inc/header.php';
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Numeric check
$pid = (isset($_GET['pid']) && is_numeric($_GET['pid']) && ($_GET['pid'] > 0)) ? $_GET['pid'] : 1;

$error = [];
$cart = [
    0 => ["pid" => 0, "quality" => 5, "price" => 5],
    1 => ["pid" => 1, "quality" => 5, "price" => 5],
    2 => ["pid" => 2, "quality" => 5, "price" => 5],
    3 => ["pid" => 288888, "quality" => 5, "price" => 5],
];

if (sizeof($cart) > 0) {
    $cart_sanitized = [];
    $total_price = 0;

    foreach ($cart as &$item) {
        if (!is_numeric($item["quality"]) || $item["quality"] < 1) {
            $error[] = [
                "type" => "warning",
                "msg" => "There exist invalid amount of a item, we already remove it.",
            ];
            continue;
        }
        $sql = $conn->prepare('SELECT name, price FROM products WHERE pid=? LIMIT 1');
        $sql->bind_param('i', $item["pid"]);
        $sql->execute();

        /*
         * fetch_array()：將讀出的資料同時以數字與欄位名稱各自存一次在陣列之中，相當於同一個值會出現兩次。
         * fetch_assoc()：將讀出的資料Key值設定為該欄位的欄位名稱。
         * fetch_row()：將讀出的資料Key值設定為依序下去的數字。
         */
        if ($result = $sql->get_result()) {
            if ($row = $result->fetch_assoc()) {
                $cart_sanitized[] = [
                    "pid" => (int)$item["pid"],
                    "quality" => (int)$item["quality"],
                    "name" => $row["name"],
                    "price" => $row["price"],
                ];
                $total_price += $row["price"] * $item["quality"];
            } else {
                $error[] = [
                    "type" => "warning",
                    "msg" => "There exist invalid item, we already remove it.",
                ];
            }
        }
    }

    try {
        $salt = bin2hex(random_bytes(8));
    } catch (Exception $e) {
        $salt = bin2hex("IERG4210");
        $error[] = [
            "type" => "server",
            "msg" => "Cannot use random_bytes() function, used without it.",
        ];
    }

    $digest = [
        "currency" => "HKD",                                    //Currency
        "business" => "sb-qawra5773820@business.example.com",   //Merchant’s email address
        "salt" => $salt,                                        //A random salt
        "cart" => $cart_sanitized,                                        //pid, quantity and current price of each selected product
        "total" => $total_price,                                //The total price of all selected products
    ];

    $hash_algorithm_rand = hash_algos()[array_rand(hash_algos())];
    $hash_digest = hash($hash_algorithm_rand, json_encode($digest));
    //$hash_digest = crypt(json_encode($digest), $salt);

    /* TODO: how to use crypt*/

    // Store them into database
    $username = "";
    $uid = -1;
    $digest["cart"] = json_encode($digest["cart"]);

    $sql = $conn->prepare("INSERT INTO `orders` (`uid`, `username`, `currency`, `salt`, `cart`, `total`) VALUES (?, ?, ?, ?, ?, ?)");
    $sql->bind_param('isssss',
        $uid,
        $username,
        $digest["currency"],
        $digest["salt"],
        $digest["cart"],
        $digest["total"]
    );

    if ($sql->execute() === TRUE) {
        $insert_id = $sql->insert_id;

        // Clean cookie
        echo "<script>localStorage.removeItem('shopping_cart');</script>";

        // TODO: Submit the form now to PayPal using programmatic form submission

        $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
        $data = ['cmd' => '_cart',
            'upload' => '1',
            'business' => 'sb-qawra5773820@business.example.com',
            'charset' => 'utf-8',
            'return'        => 'http://localhost/checkout_success.php',
            'cancel_return' => 'https://domain.com/cancel',
            'notify_url'    => 'http://localhost/checkout_verify.php'
        ];

        foreach ($cart_sanitized as $key => $item) {
            $data["item_name_" . ($key+1)] = $item["name"] . " x " . $item["quality"];
            $data["amount_" . ($key+1)] = $item["quality"] * $item["price"];
        }

        header('Location: ' . $url . http_build_query($data));
        exit();


        //https://dcblog.dev/how-to-integrate-paypal-into-php
        /*
        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        if ($result === FALSE) { //echo "error"; Handle error  }
        */

        var_dump($result);


    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }


}

//header('Content-Type: application/json');

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