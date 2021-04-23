<?php
header('Content-Type: application/json');
require 'inc/config.inc.php';

function error_interrupt_json($msg, $debug = NULL)
{
	$json_return = array(
		'success' => false,
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
$items = $_POST['items'];

$items_sanitized = [];
$prices = 0;

foreach ($items as &$item) {
	if (!is_numeric($item["quantity"]) || intval($item["quantity"]) < 1) {
		$info .= "There exist invalid amount of a item, we already remove it. ";
	}
	$sql = $db->prepare('SELECT name, price FROM products WHERE pid=? LIMIT 1');
	$sql->bind_param('i', $item["pid"]);
	$sql->execute();

	if ($result = $sql->get_result()) {
		if ($row = $result->fetch_assoc()) {
			$items_sanitized[] = [
				"pid" => intval($item["pid"]),
				"quantity" => intval($item["quantity"]),
				"name" => $row["name"],
				"price" => floatval($row["price"]),
			];
			$prices += $row["price"] * $item["quantity"];
		} else {
			$info .= 'There exist item currently not sell, we already remove it. ';
		}
	} else {
		error_interrupt_json('Sorry, our service currently has some problem!', $sql . $db->error);
	}
}

// Server generates a digest that is composed of at least
$salt = getSalt();
$digest = [
	"currency" => "HKD",                                    //Currency
	"business" => "sb-qawra5773820@business.example.com",   //Merchant’s email address
	"salt" => $salt,                                        //A random salt
	"cart" => $items_sanitized,                                        //pid, quantity and current price of each selected product
	"total" => $prices,                                //The total price of all selected products
];
$hash_digest = hash_hmac('sha1', json_encode($digest), $salt . "IERG4210");

// Server stores all the items to generate the digest into a new database table called orders
// The user could be logged in or as “guest” to purchase, store username with order in DB
$email = isset($_SESSION['email']) ? filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL) : 'Guest';
$uid = -1;
$digest["cart"] = json_encode($digest["cart"]);
$sql = $db->prepare("INSERT INTO `payments` (`uid`, `username`, `currency`, `salt`, `cart`, `total`) VALUES (?, ?, ?, ?, ?, ?)");
$sql->bind_param('isssss',
	$uid,
	$email,
	$digest["currency"],
	$digest["salt"],
	$digest["cart"],
	$digest["total"]
);

if ($sql->execute() !== TRUE) {
	error_interrupt_json('Sorry, our service currently has some problem!', $sql . $db->error);
}

// Pass the lastInsertId() and the generated digest back to the client by putting them into the hidden fields of invoice and custom, respectively
$insert_id = $sql->insert_id;

// Clear the shopping cart at the client-side
// Will be do in custom.js
// localStorage.removeItem('shopping_cart');

$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
$data = ['cmd' => '_cart',
	'upload' => '1',
	'txn_id' => $insert_id,
	'business' => 'sb-qawra5773820@business.example.com',
	'charset' => 'utf-8',
	'return' => get_full_url('checkout_success.php'),
	'cancel_return' => get_full_url('checkout_fail.php'),
	'notify_url' => get_full_url('ipn.php'),
	'custom' => '1',
	'invoice' => '1',
];

foreach ($items_sanitized as $key => $item) {
	$data["item_name_" . ($key + 1)] = $item["name"] . " x " . $item["quantity"];
	$data["amount_" . ($key + 1)] = $item["quantity"] * $item["price"];
}

echo json_encode(array(
	'url' => $url . http_build_query($data),
	'info' => $info
));


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

//var_dump($result);

