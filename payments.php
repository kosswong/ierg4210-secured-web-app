<?php
require 'inc/config.inc.php';


function checkTxnid($txnid)
{
    $db = DB();
    $sql = $db->prepare("SELECT * FROM orders WHERE txn_id=?");
    $sql->bind_param('s', $txnid);
    if ($sql->execute() && $result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            return false;
        }
    }
    return true;
}

function verifyTransaction($data)
{
    global $paypalUrl;

    $req = 'cmd=_notify-validate';
    foreach ($data as $key => $value) {
        $value = urlencode(stripslashes($value));
        $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
        $req .= "&$key=$value";
    }

    $ch = curl_init($paypalUrl);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: PHP-IPN-Verification-Script',
        'Connection: Close',
    ));
    $res = curl_exec($ch);

    if (!$res) {
        $errno = curl_errno($ch);
        $errstr = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL error: [$errno] $errstr");
    }

    $info = curl_getinfo($ch);

    // Check the http response
    $httpCode = $info['http_code'];
    if ($httpCode != 200) {
        throw new Exception("PayPal responded with http code $httpCode");
    }

    curl_close($ch);

    return $res === 'VERIFIED';
}

// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// Database settings. Change these for your database configuration.
/*$dbConfig = [
    'host' => 'localhost',
    'username' => 'user',
    'password' => 'secret',
    'name' => 'example_database'
];*/

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    //'email' => 'user@example.com',
    'return_url' => 'https://secure.s48.ierg4210.ie.cuhk.edu.hk/checkout_success.php',
    'cancel_url' => 'https://secure.s48.ierg4210.ie.cuhk.edu.hk/checkout_fail.php',
    'notify_url' => 'https://secure.s48.ierg4210.ie.cuhk.edu.hk/payments.php'
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

if (isset($_POST["txn_id"]) && isset($_POST["txn_type"])) {

    //Validate the authenticity of data by verifying that it is indeed sent from PayPal
    //Check that txn_id has not been previously processed and txn_type is cart
    if (verifyTransaction($_POST) && checkTxnid($_POST['txn_id']) && ($_POST["txn_type"] == 'cart')) {
        $txn_id = $_POST['txn_id'];
        $invoice = isset($_POST['invoice']) ? intval($_POST['invoice']) : 0;
        $database_digest = "";

        //Regenerate a digest with the data provided by PayPal (same order and algorithm)
        $db = DB();
        $sql = $db->prepare("SELECT salt, digest FROM orders WHERE id=?");
        $sql->bind_param('i', $invoice);
        $sql->execute();
        if ($sql->execute() && $result = $sql->get_result()) {
            if ($row = $result->fetch_assoc()) {
                $database_salt = $row['salt'];
                $database_digest = $row['digest'];
            }
        }
        $composed = [];
        $composed["currency"] = "HKD";    //Currency
        $composed["business"] = "sb-qawra5773820@business.example.com";   //Merchant’s email address
        $composed["salt"] = $database_salt;    //A random salt
        $composed["cart"] = [];
        $composed["total"] = $_POST['payment_gross'];           //The total price of all selected products

        $item_key = 1;
        while (isset($_POST["item_name" . $item_key])) {
            $composed["cart"][] = [
                "pid" => $_POST["item_number" . $item_key],
                "quantity" => $_POST["quantity" . $item_key],
                "price" => $_POST["mc_gross_" . $item_key]
            ];
            $item_key++;
        }
        $digest = hash_hmac('sha1', json_encode($composed), $database_salt . "IERG4210");

        //Validate the digest against the one stored in the database table orders
        if ($database_digest == $digest) {
            $composed_json = json_encode($composed);
            $composed_cart_json = json_encode($composed["cart"]);
            $db = DB();
            $sql = $db->prepare("UPDATE orders SET completed=1, cart=?, txn_id=? WHERE id=?");
            $sql->bind_param('ssi', $composed_cart_json, $txn_id, $invoice);
            $sql->execute();
        }

    }
}
//}


