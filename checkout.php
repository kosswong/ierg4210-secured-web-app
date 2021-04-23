<?php
header('Content-Type: application/json');
require 'inc/config.inc.php';

function error_interrupt_json($msg)
{
	echo json_encode(array('success' => false, 'msg' => $msg));
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
$msg = "";
$nonce = $_POST['nonce'];
$items = $_POST['items'];

$items_sanitized = [];
$prices = 0;

foreach ($items as &$item) {
	if (!is_numeric($item["quality"]) || $item["quality"] < 1) {
		$error[] = [
			"type" => "warning",
			"msg" => "There exist invalid amount of a item, we already remove it.",
		];
		continue;
	}
	$sql = $db->prepare('SELECT name, price FROM products WHERE pid=? LIMIT 1');
	$sql->bind_param('i', $item["pid"]);
	$sql->execute();

	if ($result = $sql->get_result()) {
		if ($row = $result->fetch_assoc()) {
			$items_sanitized[] = [
				"pid" => (int)$item["pid"],
				"quality" => (int)$item["quality"],
				"name" => $row["name"],
				"price" => $row["price"],
			];
			$prices += $row["price"] * $item["quality"];
		} else {
			$error[] = [
				"type" => "warning",
				"msg" => "There exist invalid item, we already remove it.",
			];
		}
	}
}

$digest = [
	"currency" => "HKD",                                    //Currency
	"business" => "sb-qawra5773820@business.example.com",   //Merchant’s email address
	"salt" => getSalt(),                                        //A random salt
	"cart" => $items_sanitized,                                        //pid, quantity and current price of each selected product
	"total" => $prices,                                //The total price of all selected products
];

$hash_algorithm_rand = hash_algos()[array_rand(hash_algos())];
$hash_digest = hash($hash_algorithm_rand, json_encode($digest));
//$hash_digest = crypt(json_encode($digest), $salt);

/* TODO: how to use crypt*/

// Store them into database
$username = "";
$uid = -1;
$digest["cart"] = json_encode($digest["cart"]);

$sql = $db->prepare("INSERT INTO `orders` (`uid`, `username`, `currency`, `salt`, `cart`, `total`) VALUES (?, ?, ?, ?, ?, ?)");
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

	// TODO: Submit the form now to PayPal using programmatic form submission

	$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
	$data = ['cmd' => '_cart',
		'upload' => '1',
		'business' => 'sb-qawra5773820@business.example.com',
		'charset' => 'utf-8',
		'return' => get_full_url('checkout_success.php'),
		'cancel_return' => get_full_url('checkout_fail.php'),
		'notify_url' => get_full_url('checkout_verify.php')
	];

	foreach ($items_sanitized as $key => $item) {
		$data["item_name_" . ($key + 1)] = $item["name"] . " x " . $item["quality"];
		$data["amount_" . ($key + 1)] = $item["quality"] * $item["price"];
	}

	echo json_encode(array('url' => $url . http_build_query($data)));


	//header('Location: ' . $url . http_build_query($data));
	//exit();


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


} else {
	echo "Error: " . $sql . "<br>" . $db->error;
}
