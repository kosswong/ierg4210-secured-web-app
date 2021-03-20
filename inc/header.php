<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>eCommerce System</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/fontawesome.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body>
<header>
    <div class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-store-alt"></i> e-Commerce System
            </a>
            <div class="dropdown">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle"
                        data-toggle="dropdown"
                        id="cart" type="button">
                    Shopping List <span class="shopping-cart-popup-item-amount">(0)</span> <span class="shopping-cart-popup-price">$0</span>
                    <i class="fas fa-shopping-cart"></i>
                </button>
                <div aria-labelledby="cart" class="dropdown-menu dropdown-menu-right">
                    <form action="http://www.paypal.com" method="POST">
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