<?php
require 'inc/config.inc.php';
$error_login = [];
$error_register = [];

function user_logout()
{
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach ($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time() - 1000);
            setcookie($name, '', time() - 1000, '/');
        }

        session_start();
        $_SESSION['username'] = 'Guest';
        $_SESSION['userid'] = -1;
        $_SESSION['msg_type'] = "success";
        $_SESSION['msg_content'] = "Logout successfully.";

        header("Location: http://localhost");
        die();
    }
}

function user_login($email, $password, $nonce)
{

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = ["type" => "warning", "msg" => "Invalid email format",];
    } else if (password_format_verify($password)) {
        $error[] = ["type" => "warning", "msg" => "Invalid password format",];
    } else if (!empty($password)) {
        $error[] = ["type" => "warning", "msg" => "Please Check You've Entered Your Password!",];
    } else {
        $error[] = ["type" => "warning", "msg" => "Please enter password.",];
    }

    $db = DB();
    $sql = $db->prepare('SELECT admin, userid, email, password, salt FROM users WHERE email=? LIMIT 1');
    $sql->bind_param('s', $email);
    $sql->execute();

    if ($result = $sql->get_result()) {

        if ($row = $result->fetch_assoc()) {
            if ($row['password'] == hash_hmac('sha1', $password, $row['salt'])) {


                $exp = time() + 3600 * 24 * 3;
                $token = [
                    'em' => $row['email'],
                    'exp' => $exp,
                    'k' => hash_hmac('sha1', $exp . $row['password'], $row['salt'])
                ];
                setcookie('auth', json_encode($token), $exp, '/', 'localhost', true, true);

                //Set up login session in database

                $sql = $db->prepare('UPDATE users SET expried=?, token=? WHERE email=?');
                $sql->bind_param('sss', $exp, $token, $email);
                if ($sql->execute() === TRUE) {
                    echo "Record updated successfully";

                    $_SESSION['email'] = $row['email'];
                    $_SESSION['userid'] = $row['userid'];
                    $_SESSION['auth'] = $token;
                    $error[] = ["type" => "success", "msg" => "Login successfully.",];
                    session_regenerate_id();

                    if ($row['admin'] == 1) {
                        header("Location: http://localhost/admin");
                        die();
                    } else {
                        header("Location: http://localhost");
                        die();
                    }


                } else {
                    echo "System error.";
                }

            } else {
                echo "Login fail: wrong password.";
            }
        } else {
            echo "Login fail: no related record.";
        }
    }

}

function user_register()
{
    try {
        if (csrf_verifyNonce($_POST['action'], $_POST['nonce']) == true) {
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error_register[] = ["type" => "warning", "msg" => "Invalid email format",];
                return;
            } else if (password_format_verify($_POST["password"]) != false) {
                $error_register[] = ["type" => "warning", "msg" => "Invalid password format",];
            } else if (!empty($_POST["password"])) {
                $error_register[] = ["type" => "warning", "msg" => "Please Check You've Entered Your Password!",];
            } else {
                $error_register[] = ["type" => "warning", "msg" => "Please enter password.",];
            }

            if (email_used($_POST["email"]) == true) {
                $error_register[] = ["type" => "warning", "msg" => "Email already used.",];
                echo "Email already used.";
            } else {
                $email = $_POST["email"];
                $salt = generate_salt();
                $algo = 'sha1';
                $password = hash_hmac($algo, $_POST['password'], $salt);
                $ip = get_ip();

                $db = DB();
                $sql = $db->prepare('INSERT INTO `users` (`userid`, `admin`, `email`, `password`, `salt`, `ip`) VALUES (NULL, 0, ?, ?, ?, ?);');
                $sql->bind_param('ssss', $email, $password, $salt, $ip);

                if ($sql->execute()) {
                    echo "Register successfully.";
                } else {
                    echo "Error: " . $sql . "<br>" . $db->error;
                }
            }
        }
    } catch (Exception $e) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
}

function user_change_password($password, $password_new)
{
    $db = DB();
    $sql = $db->prepare('SELECT password, salt FROM users WHERE email=? LIMIT 1');
    $sql->bind_param('s', $_SESSION['username']);
    $sql->execute();

    if ($result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            if ($row['password'] == hash_hmac('sha1', $password, $row['salt'])) {

                $salt = generate_salt();
                $password_new = hash_hmac('sha1', $_POST['password_new'], $salt);

                $sql = "UPDATE users SET password='$password_new', salt='$salt' WHERE email='{$_SESSION['username']}'";
                if ($db->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $db->error;
                }

                $error[] = [
                    "type" => "success",
                    "msg" => "Change successfully.",
                ];

            } else {
                $error[] = [
                    "type" => "danger",
                    "msg" => "Login fail: wrong password.",
                ];
            }
        } else {
            $error[] = [
                "type" => "warning",
                "msg" => "Login fail: no related record.",
            ];
        }
    }
}

function password_format_verify($password)
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

function email_used($email): bool
{
    $db = DB();
    $sql = $db->prepare('SELECT userid FROM users WHERE email=? LIMIT 1');
    $sql->bind_param('s', $email);
    $sql->execute();

    if ($result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            return true;
        }
    }
    return false;
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

function get_ip()
{
    $ipaddress = '';
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

require_header();

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'logout':
            if (isset($_GET['action'])) {
                user_logout();
            }
            break;
        case 'register':
            if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                user_register();
            } else {
                require 'inc/user_register.php';
            }
            break;
        case 'login':
            if (isset($_GET['action']) && isset($_POST['email']) && isset($_POST['password'])) {
                try {
                    if (csrf_verifyNonce($_POST['action'], $_POST['nonce']) == true) {
                        $template_location = 'inc/user_login.php';
                        if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                            user_login($_POST['email'], $_POST['password'], $_POST['nonce']);
                        } else {
                            $error_login[] = ["type" => "danger", "msg" => "Invalid input.",];
                        }
                    }
                } catch (Exception $e) {
                    header('HTTP/1.0 403 Forbidden');
                    exit;
                }
            }else{
                require 'inc/user_login.php';
            }
            break;
        case 'change_password':
            if (isset($_POST['action']) && isset($_POST['password']) && isset($_POST['password_new'])) {
                user_change_password($_POST['password'], $_POST['password_new']);
            }
            break;
        case 'password':
            if (isset($_GET['action'])) {
                require 'inc/user_change_password.php';
            }
            break;
        default:
            require 'inc/error.php';
    }
}else{
    require 'inc/error.php';
}

require_footer();
