<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <title>eCommerce System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome-all.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
</head>

<body>
<header>
    <div class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <i class="fas fa-store-alt"></i> e-Commerce System
            </a>
            <div class="dropdown">
                <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle"
                        data-toggle="dropdown"
                        id="cart" type="button">
                    Shopping List (3) $4.0
                    <i class="fas fa-shopping-cart"></i>
                </button>
                <div aria-labelledby="cart" class="dropdown-menu dropdown-menu-right">
                    <div class="container shopping-list">
                        <form action="http://www.paypal.com" method="POST">

                            <div class="form-row">
                                <div class="form-group col-12"><label for="item_a">Item A</label></div>
                                <div class="form-group col-6">
                                    <input class="form-control" id="item_a" min="0" name="item_a" type="number"
                                           value="1">
                                </div>
                                <div class="form-group col-6 text-right">$30.7</div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12"><label for="item_b">Item B</label></div>
                                <div class="form-group col-6">
                                    <input class="form-control" id="item_b" min="0" type="number" value="1">
                                </div>
                                <div class="form-group col-6 text-right">$38.7</div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12"><label for="item_c">Item C</label></div>
                                <div class="form-group col-6">
                                    <input class="form-control" id="item_c" min="0" type="number" value="3">
                                </div>
                                <div class="form-group col-6 text-right">$12.7</div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-6">Sum:</div>
                                <div class="form-group col-6 text-right">$82.1</div>
                                <button class="btn btn-primary btn-block" type="submit">Check Out</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>