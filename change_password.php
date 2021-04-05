<?php
require 'inc/header.php';
?>

<?php
// Connect mySQL
$error = [];
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    $error[] = ["type" => "danger", "msg" => "We are sorry that the service currently not available.",];
}

if(auth()){
    echo "test";
}else{
    echo "test2";
}

function auth()
{// returns email if valid, otherwise false
    if (!empty($_SESSION['auth'])) return $_SESSION['auth']['em'];
    if (!empty($_COOKIE['auth'])) {
        if ($t = json_decode($_COOKIE['auth'], true)) {
            if (time() > $t['exp']) return false;
            global $conn; // validate if token matches our record
            $q = $conn->prepare('SELECT salt, password FROM users WHERE email = ?');
            if ($q->execute(array($t['em']))
                && ($r = $q->fetch())
                && $t['k'] == hash_hmac('sha1',
                    $t['exp'] . $r['password'], $r['salt'])) {
                $_SESSION['auth'] = $_COOKIE['auth'];
                return $t['em'];
            }
            return false; // or header('Location: login.php');exit();
        }
    }
}

if(isset($_GET['action']) && ($_GET['action'] == 'logout')){
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }

        session_start();
        $_SESSION['username'] = 'Guest';
        $_SESSION['userid'] = -1;

        $error[] = [
            "type" => "success",
            "msg" => "Logout successfully.",
        ];
    }
}

if (isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['action']) &&
    ($_POST['action'] == 'login' || $_POST['action'] == 'register')) {

    $email = $_POST['email'];
    $password = $_POST['password'];

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

        $sql = $conn->prepare('SELECT admin, userid, email, password, salt FROM users WHERE email=? LIMIT 1');
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

                    if($row['admin'] == 1) $_SESSION['admin'] = $row['admin'];
                    $_SESSION['username'] = $row['email'];
                    $_SESSION['userid'] = $row['userid'];
                    $_SESSION['auth'] = $token;
                    session_regenerate_id();

                    $error[] = [
                        "type" => "success",
                        "msg" => "Login successfully.",
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

    } else if ($_POST['action'] == 'register') {

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
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
}

/*
global $db;
$q = $db->prepare(
    'SELECT salt, password FROM users WHERE email = ?');
if ($q->execute(array($_POST['em']))&&($r=$q->fetch())
    && $r['password']==hash_hmac('sha1',
        $_POST['password'], $r['salt'])){
// When successfully authenticated,
// 1. create authentication token
// 2. redirect to admin.php
} else {
    throw new Exception('auth-error');
}

*/


$cat_list = '';     //Categories list
$p_list = '';       //Product list

// Numeric check
$current_catid = (isset($_GET['catid']) && is_numeric($_GET['catid']) && ($_GET['catid'] > 0)) ? $_GET['catid'] : 1;
$current_catname = '';

// Attempt select query execution
$sql_cat = "SELECT * FROM categories";
if ($result = mysqli_query($conn, $sql_cat)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if ($current_catid == $row['catid']) {
                $current_catname = $row['name'];

                // Prevent SQL injection
                $query = $conn->prepare('SELECT * FROM products WHERE catid = ?');
                $query->bind_param('i', $current_catid); // 'i' specifies the variable type => 'integer'
                $query->execute();

                if ($result_product = $query->get_result()) {
                    if (mysqli_num_rows($result_product) > 0) {
                        while ($product = $result_product->fetch_array()) {
                            $p_list .= '<div class="col-4">'
                                . '<div class="card">'
                                . '<img alt="Great Item" class="card-img-top" src="img/product/s/' . $product['image'] . '">'
                                . '<div class="card-body">'
                                . '<h5 class="card-title">' . $product['name'] . '</h5>'
                                . '<p class="card-text">HK$' . $product['price'] . '</p>'
                                . '<a class="btn btn-primary" href="product.php?pid=' . $product['pid'] . '">Detail</a>'
                                . '<button type="button" class="btn btn-warning btn-add-to-cart" id="item-' . $product['pid'] . '" data-id="' . $product['pid'] . '">
Add to cart</button>'
                                . '</div>'
                                . '</div>'
                                . '</div>';
                        }
                        // Close result set
                        mysqli_free_result($result_product);
                    } else {
                        $p_list = "No records matching your query were found.";
                    }
                } else {
                    echo "ERROR: Could not able to execute $sql_cat. " . mysqli_error($conn);
                }

            }
            $cat_list .= '<a class="list-group-item list-group-item-action ' . ($current_catid == $row['catid'] ? 'active' : '') . '"
                           href="index.php?catid=' . $row['catid'] . '">' . $row['name'] . '</a>';
        }
        // Close result set
        mysqli_free_result($result);
    } else {
        $cat_list = "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql_cat. " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>

    <form class="form-signin" method="post">
        <input type="hidden" name="action" value="login">

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