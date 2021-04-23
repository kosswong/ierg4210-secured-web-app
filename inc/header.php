<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
$email = isset($_SESSION['email']) ? filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL) : 'Guest';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>eCommerce System</title>
    <link href="../css/fontawesome.css" rel="stylesheet" type="text/css">
    <link href="../css/theme.css" rel="stylesheet">
    <link href="../css/font.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="navbar navbar-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    Welcome, <?= $email ?>!
                    <a href='user.php?action=login'>Shopping History</a> |
                    <?php
                    if ($email == 'Guest') {
                        echo "<a href='user.php?action=login'>Login</a> | <a href='user.php?action=register'>Register</a>";
                    } else {
                        echo "<a href='user.php?action=password'>Change Password</a> | <a href='user.php?action=logout'>Logout</a>";
                    }
                    ?>
                </a>
                <div class="dropdown">
                    <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle"
                            data-toggle="dropdown"
                            id="cart" type="button">
                        Shopping List <span class="shopping-cart-popup-item-amount">(0)</span> <span
                                class="shopping-cart-popup-price">$0</span>
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                    <div aria-labelledby="cart" class="dropdown-menu dropdown-menu-right">
                        <form action="checkout.php" method="POST" id="shopping_cart_form">
                            <input type="hidden" name="nonce" id="nonce_checkout" value="<?= csrf_getNonce('shop') ?>">
                            <input type="hidden" name="cmd" value="_cart">
                            <input type="hidden" name="upload" value="1">
                            <input type="hidden" name="business" value="sb-qawra5773820@business.example.com">
                            <INPUT TYPE="hidden" name="charset" value="utf-8">
                            <input type="hidden" name="item_name_1" value="Item Name 1">
                            <input type="hidden" name="amount_1" value="1.00">
                            <input type="hidden" name="shipping_1" value="1.75">
                            <input type="hidden" name="item_name_2" value="Item Name 2">
                            <input type="hidden" name="amount_2" value="2.00">
                            <input type="hidden" name="shipping_2" value="2.50">
                            <div class="container shopping-list">
                                <div id="shopping-list">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-6">Sum (Estimate):</div>
                                    <div class="form-group col-6 text-right shopping-cart-popup-price">$ 0</div>
                                    <button class="btn btn-primary btn-block" type="submit">Check Out</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">

    <?php
    if (isset($_SESSION['msg']) && isset($_SESSION['msg']['type']) && isset($_SESSION['msg']['content'])) {
        $msg_type = htmlspecialchars(strip_tags($_SESSION['msg']['type']));
        $msg_content = htmlspecialchars(strip_tags($_SESSION['msg']['content']));
        echo '<div class="card bg-' . $msg_type . ' text-white shadow my-5">'
            . '<div class="card-body">'. $msg_content. '</div>'
            . '</div>';
    }
    ?>