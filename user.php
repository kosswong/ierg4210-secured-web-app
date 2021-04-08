<?php
require 'inc/config.inc.php';

function user_logout($custom_message = '')
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
        $_SESSION['msg'] = ["type" => "success", "msg" => ($custom_message != '' ? $custom_message : "Login successfully.")];

        header("Location: http://localhost");
        die();
    }
}

function user_login($email, $password, $nonce)
{
    try {
        if (csrf_verifyNonce('login', $nonce) == true) {
            if ((validatePassword($password) != false) || (validateEmail($email, true) != false)) {
                $_SESSION['msg'] = ['type' => 'danger', 'content' => validateEmail($email, true).validatePassword($_POST["password"])];
                return;
            } else {
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

                            $sql = $db->prepare('UPDATE users SET expried=?, token=? WHERE email=?');
                            $sql->bind_param('sss', $exp, $token, $email);
                            if ($sql->execute() === TRUE) {
                                echo "Record updated successfully";
                                $_SESSION['email'] = $row['email'];
                                $_SESSION['userid'] = $row['userid'];
                                $_SESSION['auth'] = $token;
                                $_SESSION['msg'] = ["type" => "success", "msg" => "Login successfully."];
                                session_regenerate_id();
                                if ($row['admin'] == 1) {
                                    header("Location: http://localhost/admin");
                                    die();
                                } else {
                                    header("Location: http://localhost");
                                    die();
                                }
                            } else {
                                $_SESSION['msg'] = ["type" => "warning", "content" => "Service currently not available."];
                            }
                        } else {
                            $_SESSION['msg'] = ["type" => "danger", "content" => "Login fail: wrong password."];
                        }
                    } else {
                        $_SESSION['msg'] = ["type" => "warning", "content" => "Login fail: no related record."];
                    }
                }
            }
        } else {
            $_SESSION['msg'] = ["type" => "danger", "content" => "Invalid operation.",];
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = ['type' => 'info', 'content' => 'Bad session.'];
    }
}

function user_register($email, $password, $nonce)
{
    try {
        if (csrf_verifyNonce('register', $nonce) == true) {
            if ((validatePassword($password) != false) || (validateEmail($email) != false)) {
                $_SESSION['msg'] = ['type' => 'danger', 'content' => validateEmail($email).validatePassword($_POST["password"])];
                return;
            } else {
                $algo = 'sha1';
                $salt = generate_salt();
                $password_encrypted = hash_hmac($algo, $password, $salt);
                $ip = get_ip();

                $db = DB();
                $sql = $db->prepare('INSERT INTO `users` (`userid`, `admin`, `email`, `password`, `salt`, `ip`) VALUES (NULL, 0, ?, ?, ?, ?);');
                $sql->bind_param('ssss', $email, $password_encrypted, $salt, $ip);
                if ($sql->execute()) {
                    $_SESSION['msg'] = ['type' => 'success', 'content' => 'Register successfully.'];
                } else {
                    $_SESSION['msg'] = ['type' => 'error', 'content' => 'Database error.'];
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = ['type' => 'info', 'content' => 'Bad session.'];
    }
}

function user_change_password($password, $password_new, $nonce)
{
    $email = $_SESSION['email'];
    try {
        if (csrf_verifyNonce('password', $nonce) == true) {
            if (validatePassword($password) != false) {
                $_SESSION['msg'] = ['type' => 'danger', 'content' => validatePassword($password)];
                return;
            } else {
                $db = DB();
                $sql = $db->prepare('SELECT password, salt FROM users WHERE email=? LIMIT 1');
                $sql->bind_param('s', $_SESSION['email']);
                $sql->execute();

                if ($result = $sql->get_result()) {
                    if ($row = $result->fetch_assoc()) {
                        if ($row['password'] == hash_hmac('sha1', $password, $row['salt'])) {
                            $salt = generate_salt();
                            $password_new_encrypted = hash_hmac('sha1', $password_new, $salt);
                            $sql = "UPDATE users SET password='$password_new_encrypted', salt='$salt' WHERE email='$email'";
                            if ($db->query($sql) === TRUE) {
                                user_logout("New record created successfully.");
                            } else {
                                $_SESSION['msg'] = ['type' => 'info', 'content' => 'Database error.'];
                            }
                        } else {
                            $_SESSION['msg'] = ['type' => 'danger', 'content' => 'Fail: wrong password.'];
                        }
                    } else {
                        $_SESSION['msg'] = ['type' => 'warning', 'content' => 'Fail: no related record.'];
                    }
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['msg'] = ['type' => 'info', 'content' => 'Bad session.'];
    }
}

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'logout':
            if (isset($_GET['action'])) {
                user_logout();
            } else {
                $_SESSION['msg'] = ['type' => 'info', 'content' => 'Bad operation.'];
                require_full_page('inc/error.php');
            }
            break;
        case 'register':
            if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                user_register($_POST['email'], $_POST['password'], $_POST['nonce']);
            }
            require_full_page('inc/user_register.php');
            break;
        case 'login':
            if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                user_login($_POST['email'], $_POST['password'], $_POST['nonce']);
            }
            require_full_page('inc/user_login.php');
            break;
        case 'password':
            if (isset($_POST['action']) && isset($_POST['password']) && isset($_POST['password_new']) && isset($_POST['nonce'])) {
                user_change_password($_POST['password'], $_POST['password_new'], $_POST['nonce']);
            }
            require_full_page('inc/user_password.php');
            break;
        default:
            require_full_page('inc/error.php');
    }
} else {
    require_full_page('inc/error.php');
}