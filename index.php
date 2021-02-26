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


<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li aria-current="page" class="breadcrumb-item active">Category 1</li>
        </ol>
    </nav>
    <div class="featurette">
        <h2 class="featurette-heading">Shop Heath.
            <span class="text-muted">It'll blow your mind.</span>
        </h2>
        <p class="lead"> “Health is a state of complete harmony of the body, mind and spirit. When one is free from
            physical disabilities and mental distractions, the gates of the soul open.” – B.K.S. Iyengar</p>
    </div>
</main>


<section class="position-relative bg-light" id="categories">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="list-group" id="list-tab" role="tablist">
                    <a aria-controls="list-a" class="list-group-item list-group-item-action active" data-bs-toggle="list"
                       href="#list-a" id="list-a-list" role="tab">Category 1</a>
                    <a aria-controls="list-b" class="list-group-item list-group-item-action" data-bs-toggle="list"
                       href="#list-b" id="list-b-list" role="tab">Groceries</a>
                    <a aria-controls="list-c" class="list-group-item list-group-item-action" data-bs-toggle="list"
                       href="#list-c" id="list-c-list" role="tab">Biscuits, Snacks & Confectionery</a>
                    <a aria-controls="list-d" class="list-group-item list-group-item-action" data-bs-toggle="list"
                       href="#list-d" id="list-d-list" role="tab">Household</a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="nav-tabContent">

                    <div aria-labelledby="list-a-list" class="tab-pane fade show active" id="list-a"
                         role="tabpanel">
                        <div class="row">
                            <div class="col-4">
                                <div class="card">
                                    <img alt="Great Item" class="card-img-top" src="img/product/8829239132190.jpg">
                                    <div class="card-body">
                                        <h5 class="card-title">Great Item</h5>
                                        <p class="card-text">HK$30.90</p>
                                        <a class="btn btn-primary" href="product.html">Detail</a>
                                        <a class="btn btn-warning" href="product.html">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <img alt="Yummy Item" class="card-img-top" src="img/product/8863388205086.jpg">
                                    <div class="card-body">
                                        <h5 class="card-title">Yummy Item</h5>
                                        <p class="card-text">HK$12.90</p>
                                        <a class="btn btn-primary" href="product.html">Detail</a>
                                        <a class="btn btn-warning" href="product.html">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <img alt="Good Item" class="card-img-top" src="img/product/8879818801182.jpg">
                                    <div class="card-body">
                                        <h5 class="card-title">Good Item</h5>
                                        <p class="card-text">HK$7.90</p>
                                        <a class="btn btn-primary" href="product.html">Detail</a>
                                        <a class="btn btn-warning" href="product.html">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <img alt="ORGANIC SENCHA TEA BAG" class="card-img-top" src="img/product/8885417967646.jpg">
                                    <div class="card-body">
                                        <h5 class="card-title">ORGANIC SENCHA TEA BAG</h5>
                                        <p class="card-text">HK$30.90</p>
                                        <a class="btn btn-primary" href="product.html">Detail</a>
                                        <a class="btn btn-warning" href="product.html">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <img alt="ORGANIC SENCHA TEA BAG" class="card-img-top" src="img/product/8893729832990.jpg">
                                    <div class="card-body">
                                        <h5 class="card-title">ORGANIC SENCHA TEA BAG</h5>
                                        <p class="card-text">HK$30.90</p>
                                        <a class="btn btn-primary" href="product.html">Detail</a>
                                        <a class="btn btn-warning" href="product.html">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card">
                                    <img alt="ORGANIC SENCHA TEA BAG" class="card-img-top" src="img/product/8895894618142.jpg">
                                    <div class="card-body">
                                        <h5 class="card-title">ORGANIC SENCHA TEA BAG</h5>
                                        <p class="card-text">HK$30.90</p>
                                        <a class="btn btn-primary" href="product.html">Detail</a>
                                        <a class="btn btn-warning" href="product.html">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div aria-labelledby="list-b-list" class="tab-pane fade" id="list-b" role="tabpanel">No
                        item
                    </div>

                    <div aria-labelledby="list-c-list" class="tab-pane fade" id="list-c" role="tabpanel">
                        <div class="col-4">
                            <div class="card">
                                <img alt="ORGANIC SENCHA TEA BAG" class="card-img-top" src="img/product/8994473017374.jpg">
                                <div class="card-body">
                                    <h5 class="card-title">ORGANIC SENCHA TEA BAG</h5>
                                    <p class="card-text">HK$30.90</p>
                                    <a class="btn btn-primary" href="product.html">Detail</a>
                                    <a class="btn btn-warning" href="product.html">Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div aria-labelledby="list-d-list" class="tab-pane fade" id="list-d" role="tabpanel">
                        No item
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<footer class="container">
    <p>&copy; Company 2021</p>
</footer>


<script src="js/jquery-3.2.1.slim.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
