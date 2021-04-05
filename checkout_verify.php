<?php

define("USE_SANDBOX", 'Y');

// Read POST data
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2){
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}
if (USE_SANDBOX == 'Y') {
    $paypal_url = "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr";
} else {
    $paypal_url = "https://ipnpb.paypal.com/cgi-bin/webscr";
}
$ch = curl_init($paypal_url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);

if (curl_errno($ch) != 0) {

    throw new \Exception("Can't connect to PayPal to validate IPN message: ".curl_error($ch));

    return true;
} else {

    if (preg_match("/VERIFIED/", $res)) {
        echo "success";
        //payment complete, you should send en email here.
    } else {
        echo "IPN not verified";
    }
}
curl_close($ch);

?>