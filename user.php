<?php
require 'inc/config.inc.php';
$db = DB();
$error = [];

if (auth()) {
    //echo "test";
} else {
    //echo "test2";
}

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'logout':
            if (isset($_GET['action'])) {
                user_logout();
            }
            break;
        case 'register':
            if (isset($_POST['action'])) {
                user_register();
            }
            break;
        case 'login':
            if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                user_login($_POST['email'], $_POST['password'], $_POST['nonce']);
            }
            break;
        case 'change_password':
            if (isset($_POST['action']) && isset($_POST['password']) && isset($_POST['password_new'])) {
                user_change_password();
            }
            break;
    }
}

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
    try {
        if (csrf_verifyNonce($_POST['action'], $_POST['nonce']) == true) {
            $db = DB();
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = ["type" => "warning", "msg" => "Invalid email format",];
            } else if (!empty($_POST["password"]) && $_POST["password"]) {
                if (strlen($_POST["password"]) <= '8') {
                    $passwordErr = "Your Password Must Contain At Least 8 Characters!";
                } elseif (!preg_match("#[0-9]+#", $password)) {
                    $passwordErr = "Your Password Must Contain At Least 1 Number!";
                } elseif (!preg_match("#[A-Z]+#", $password)) {
                    $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
                } elseif (!preg_match("#[a-z]+#", $password)) {
                    $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
                }
            } else if (!empty($_POST["password"])) {
                $error[] = ["type" => "warning", "msg" => "Please Check You've Entered Your Password!",];
            } else {
                $error[] = ["type" => "warning", "msg" => "Please enter password.",];
            }

            if ($_POST['action'] == 'login') {
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

                            if ($row['admin'] == 1) $_SESSION['admin'] = $row['admin'];
                            $_SESSION['username'] = $row['email'];
                            $_SESSION['userid'] = $row['userid'];
                            $_SESSION['auth'] = $token;
                            session_regenerate_id();

                            $error[] = [
                                "type" => "success",
                                "msg" => "Login successfully.",
                            ];

                            header("Location: http://localhost");
                            die();

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
        }else{
            header('HTTP/1.0 403 Forbidden');
            exit;
        }
    } catch (Exception $e) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
}

function user_register()
{
    $db = DB();
    if ($_POST['action'] == 'register') {
        try {
            $salt = bin2hex(random_bytes(8));
        } catch (Exception $e) {
            $salt = bin2hex("IERG4210");
            $error[] = [
                "type" => "server",
                "msg" => "Cannot use random_bytes() function, used without it.",
            ];
        }
        $email = "test@test.com";
        $password = hash_hmac('sha1', $_POST['password'], $salt);

        $sql = "INSERT INTO `users` (`userid`, `admin`, `email`, `password`, `salt`) VALUES (NULL, 1, '$email', '$password', '$salt');";
        if ($db->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $db->error;
        }
    }
}

function user_change_password($password, $password_new)
{
    $db = DB();
    $password = $_POST['password'];
    $password_new = $_POST['password_new'];

    $sql = $db->prepare('SELECT password, salt FROM users WHERE email=? LIMIT 1');
    $sql->bind_param('s', $_SESSION['username']);
    $sql->execute();

    if ($result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            if ($row['password'] == hash_hmac('sha1', $password, $row['salt'])) {

                try {
                    $salt = bin2hex(random_bytes(8));
                } catch (Exception $e) {
                    $salt = bin2hex("IERG4210");
                    $error[] = [
                        "type" => "server",
                        "msg" => "Cannot use random_bytes() function, used without it.",
                    ];
                }

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

require_header();
?>

    <form class="form-signin" method="post">
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="nonce" value="<?= csrf_getNonce('login') ?>">

        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required
               autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <?php foreach ($error as $value) { ?>
            <div class="alert alert-<?= $value["type"] ?>" role="alert">
                <?= $value["msg"] ?>
            </div>
        <?php } ?>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in / Register</button>
    </form>

<?php require 'inc/footer.php'; ?>