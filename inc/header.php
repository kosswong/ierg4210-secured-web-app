<?php
if (!defined('IERG4210')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>eCommerce System</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/fontawesome.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
</head>

<body>
<header>
    <div class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                Welcome, <?= $email ? $email : 'Guest' ?>!
                <?php
                if (!$email) {
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


                    <form action="https://api-3t.sandbox.paypal.com/nvp" method="POST">
                        <input type="hidden" name="nonce" value="<?= csrf_getNonce('shop') ?>">
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
    <?php if (isset($_SESSION['msg']) && isset($_SESSION['msg']['type']) && isset($_SESSION['msg']['content'])) { ?>
        <div class="alert alert-<?= $_SESSION['msg']['type'] ?>" role="alert">
            <?= $_SESSION['msg']['content'] ?>
        </div>
    <?php } ?>