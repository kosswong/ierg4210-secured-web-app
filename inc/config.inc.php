<?php

defined('IERG4210') or define('IERG4210', 'WhySoChur');

session_start();

function DB()
{
    $db = mysqli_connect("localhost", "root", "", "test");
    if ($db === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    return $db;
}

function require_header(){
    require 'header.php';
}

function require_footer(){
    require 'footer.php';
}

function auth()
{// returns email if valid, otherwise false
    if (!empty($_SESSION['auth'])) return $_SESSION['auth']['em'];
    if (!empty($_COOKIE['auth'])) {
        if ($t = json_decode($_COOKIE['auth'], true)) {
            if (time() > $t['exp']) return false;
            global $db; // validate if token matches our record
            $q = $db->prepare('SELECT salt, password FROM users WHERE email = ?');
            if ($q->execute(array($t['em']))
                && ($r = $q->fetch())
                && $t['k'] == hash_hmac('sha1',
                    $t['exp'] . $r['password'], $r['salt'])) {
                $_SESSION['auth'] = $_COOKIE['auth'];
                return $t['em'];
            }
            return false; // or header('Location: user.php');exit();
        }
    }
}

function csrf_getNonce($action){
    $nonce = mt_rand().mt_rand();
    if(!isset($_SESSION['csrf_nonce'])){
        $_SESSION['csrf_nonce'] = array();
    }
    $_SESSION['csrf_nonce'][$action] = $nonce;
    return $nonce;
}

function csrf_verifyNonce($action, $receivedNonce){
    if(isset($receivedNonce) && $_SESSION['csrf_nonce'][$action] == $receivedNonce){
        if($_SESSION['authtoken']==null){
            unset($_SESSION['csrf_nonce'][$action]);
        }
        return true;
    }
    throw new Exception('csrf-attack');
}

?>