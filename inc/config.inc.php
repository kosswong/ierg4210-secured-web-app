<?php
defined('IERG4210') or define('IERG4210', 'allAccessTheSite');

session_set_cookie_params(0, '/', 'localhost', true, true);
session_start();

$_SESSION['msg'] = [];

function DB(): mysqli
{
    $db = mysqli_connect("localhost", "root", "", "test");
    if ($db === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
    return $db;
}

function redirect_link($extend_url = '')
{
    $website_link = "http://localhost/";
    header("Location: ".$website_link.$extend_url);
    die();
}

function require_header()
{
    require 'header.php';
}

function require_footer()
{
    require 'footer.php';
}

function require_full_page($template)
{
    require_header();
    /** @noinspection PhpIncludeInspection */
    if (file_exists($template)) {
        require $template;
    } else {
        require "error.php";
    }
    require_footer();
}

function auth(): bool
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

function csrf_getNonce($action): string
{
    $nonce = mt_rand() . mt_rand();
    if (!isset($_SESSION['csrf_nonce'])) {
        $_SESSION['csrf_nonce'] = array();
    }
    $_SESSION['csrf_nonce'][$action] = $nonce;
    return $nonce;
}

function csrf_verifyNonce($action, $receivedNonce): bool
{
    if (!isset($_SESSION['csrf_nonce'][$action])) {
        return false;
    }
    if (isset($receivedNonce) && $_SESSION['csrf_nonce'][$action] == $receivedNonce) {
        if (!isset($_SESSION['authtoken']) || $_SESSION['authtoken'] == null) {
            unset($_SESSION['csrf_nonce'][$action]);
        }
        return true;
    }
    throw new Exception('csrf-attack');
}


function get_ip(): string
{
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function validateEmail($email, $login = false)
{
    // Valid email: false, Invalid email: true
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format";
    }else{
        $db = DB();
        $sql = $db->prepare('SELECT email FROM users WHERE email=? LIMIT 1');
        $sql->bind_param('s', $email);
        $sql->execute();
        if ($result = $sql->get_result()) {
            if ($row = $result->fetch_assoc()) {
                if($login == true){
                    return false;
                }else{
                    return "Email address already been used.";
                }
            }
        }
    }
    return false;
}

function validatePassword($password)
{
    if (strlen($password) <= '8') {
        return "Your Password Must Contain At Least 8 Characters!";
    } elseif (!preg_match("#[0-9]+#", $password)) {
        return "Your Password Must Contain At Least 1 Number!";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        return "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        return "Your Password Must Contain At Least 1 Lowercase Letter!";
    }
    return false;
}

function auth_admin()
{
    if (isset($_SESSION['4210SHOP']) && !empty($_SESSION['4210SHOP'])) {
        return $_SESSION['4210SHOP']['em'];
    }
    if (isset($_COOKIE['auth']) && $auth = json_decode(stripcslashes($_COOKIE['auth']), true)) {

        if (!isset($_SESSION['email'])
            || !isset($auth['em'])
            || $auth['em'] != $_SESSION['email']
            || time() > $auth['exp']
        ) {
            return false;
        }

        $db = DB();
        $sql = $db->prepare('SELECT password, salt, expried FROM users WHERE email=? AND admin=1 LIMIT 1');
        $sql->bind_param('s', $_SESSION['email']);
        $sql->execute();

        if ($result = $sql->get_result()) {
            if ($row = $result->fetch_assoc()) {
                $real_key = hash_hmac('sha1', $auth['exp'] . $row['password'] . "IERG4210", $row['salt'] . "IERG4210");
                if ($real_key == $auth['k']) {
                    $_SESSION['4210SHOP'] = $auth;
                    return $auth['em'];
                }
            }
        }
    }
    return false;
}

function uploadImage($insert_id) {
    $target_dir = "../img/product/o/";
    $target_resized_dir = "../img/product/s/";

    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $insert_id. '.' . $imageFileType;
    $uploadOk = 1;
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    //Maximum 10MB
    if ($_FILES["image"]["size"] >= 10000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check file format, only jpg/gif/png is allowed
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }else {
        // Make a resized version image
        $target_resized_file = $target_resized_dir . $insert_id . '.' . $imageFileType;
        if ($imageFileType == "jpg" || $imageFileType == "jpeg") {
            $image = imagecreatefromjpeg($_FILES["image"]["tmp_name"]);
        }else if($imageFileType == "png"){
            $image = imagecreatefrompng($_FILES["image"]["tmp_name"]);
        }else if($imageFileType == "gif"){
            $image = imagecreatefromgif($_FILES["image"]["tmp_name"]);
        }
        $imgResized = imagescale($image, 200, 200);
        imagejpeg($imgResized, $target_resized_file);
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            return $imageFileType;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    }
}

function generate_salt()
{
    try {
        $salt = bin2hex(random_bytes(8));
    } catch (Exception $e) {
        $salt = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8 / strlen($x)))), 1, 8);
    }
    return $salt;
    /*
    $db = DB();
    $sql = $db->prepare('SELECT count(*) FROM users WHERE salt=? LIMIT 1');
    $sql->bind_param('s', $salt);
    $sql->execute();

    if ($result = $sql->get_result()) {
        if (!($row = $result->fetch_assoc())) {
            return $salt;
        }
    }
    */
}
