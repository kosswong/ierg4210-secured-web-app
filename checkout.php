<?php
//header('Content-Type: application/json');
require 'inc/config.inc.php';

function error_interrupt_json($msg, $debug = NULL)
{
    $json_return = array(
        'success' => false,
        'url' => './index.php',
        'msg' => $msg,
        'debug' => $debug,
    );
    echo json_encode($json_return);
    exit;
}

try {
    if (!isset($_POST['nonce']) || !(csrf_verifyNonce('shop', $_POST['nonce']) == true)) {
        error_interrupt_json('Invalid session!');
    } else if (!isset($_POST['items']) || !is_array($_POST['items']) || sizeof($_POST['items']) < 1) {
        error_interrupt_json('Your shopping cart is empty!');
    }
} catch (Exception $e) {
    error_interrupt_json('Cannot verify the nonce!');
}

$db = DB();
$success = true;
$info = NULL;
$nonce = $_POST['nonce'];
$items = isset($_POST['items']) ? $_POST['items'] : [];
$item_names = [];

$composed = [];
$composed["currency"] = "HKD";    //Currency
$composed["business"] = "sb-qawra5773820@business.example.com";   //Merchant’s email address
$composed["salt"] = getSalt();    //A random salt
$composed["cart"] = [];           //The pid and quantity of each selected product (Is quantity positive number?
// and the current price of each selected product gathered from DB
$composed["total"] = 0;           //The total price of all selected products

//The pid and quantity of each selected product
foreach ($items as $key => $item) {
    if (!is_numeric($item["quantity"]) || intval($item["quantity"]) < 1) {
        $info .= "There exist invalid amount of a item, we already remove it. ";
    }
    $sql = $db->prepare('SELECT name, price FROM products WHERE pid=? LIMIT 1');
    $sql->bind_param('i', $item["pid"]);
    $sql->execute();
    if ($result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            $item_names[] = $row["name"];
            $composed["cart"][] = [
                "pid" => intval($item["pid"]) . "",
                "quantity" => intval($item["quantity"]) . "",
                "price" => number_format((float)$row["price"], 2, '.', '') . ""
            ];
            $composed["total"] += $row["price"] * $item["quantity"];
        } else {
            $info .= 'There exist item currently not sell, we already remove it. ';
        }
    } else {
        error_interrupt_json('Sorry, our service currently has some problem!', $sql . $db->error);
    }
}

// Generates a digest
$composed["total"] = number_format((float)$composed["total"], 2, '.', '') . "";
$digest = hash_hmac('sha1', json_encode($composed), $composed["salt"] . "IERG4210");

// Server stores all the items to generate the digest into a new database table called orders
// The user could be logged in or as “guest” to purchase, store username with order in DB
$uid = 0;
$email = isset($_SESSION['email']) ? filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL) : 'Guest';
$cart_json_encoded = json_encode($composed["cart"]);
$sql = $db->prepare("INSERT INTO `orders` (`uid`, `username`, `currency`, `salt`, `cart`, `digest`, `total`) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param('issssss',
    $uid,
    $email,
    $composed["currency"],
    $composed["salt"],
    $cart_json_encoded,
    $digest,
    $composed["total"]
);

if ($sql->execute() !== TRUE) {
    error_interrupt_json('Sorry, our service currently has some problem!', $sql . $db->error);
}

// Pass the lastInsertId() and the generated digest back to the client by putting them into the hidden fields of invoice and custom, respectively
$insert_id = $sql->insert_id;

$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
$data = ['cmd' => '_cart',
    'upload' => '1',
    'business' => 'sb-qawra5773820@business.example.com',
    'charset' => 'utf-8',
    'return' => get_full_url('checkout_success.php'),
    'cancel_return' => get_full_url('checkout_fail.php'),
    'notify_url' => get_full_url('payments.php'),
    'invoice' => $insert_id,
    'custom' => $digest,
];

foreach ($composed["cart"] as $key => $item) {
    $data["item_number_" . ($key + 1)] = $item["pid"];
    $data["item_name_" . ($key + 1)] = $item_names[$key];
    $data["quantity_" . ($key + 1)] = $item["quantity"];
    $data["amount_" . ($key + 1)] = $item["price"];
}

//Clear the shopping cart at the client-side
//Submit the form now to PayPal using programmatic form submission
echo json_encode(array(
    'url' => $url . http_build_query($data),
    'info' => $info
));