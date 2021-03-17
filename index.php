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
                    Shopping List <span class="shopping-cart-popup-item-amount"></span> <span class="shopping-cart-popup-price">$4.0</span>
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
<?php
// Connect mySQL
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$cat_list = ''; //Categories list
$p_list = ''; //Product list

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
                                . '<button type="button" class="btn btn-warning btn-add-to-cart" id="item-' . $product['pid'] . '" data-id="' . $product['pid'] . '" data-name="' . $product['name'] . '" data-price="' . $product['price'] . '">
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

<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><?php echo $current_catname ?></li>
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
                    <?= $cat_list ?>
                </div>
            </div>
            <div class="col-9">
                <div class="row">
                    <?= $p_list ?>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>

<script>
    let cart = {};
    let cart_detail = {};
    cart.items = [];

    $(document).ready(function () {

        // Check local data, it nothing exist, then create
        if (localStorage.getItem("shopping_cart") != null) {
            let items = JSON.parse(localStorage.getItem("shopping_cart")).items;
            for (let i = 0; i < items.length; i++) {
                if($.isNumeric(items[i].amount) && $.isNumeric(items[i].price)) {
                    addItemOnPresenter(i, items[i]);
                    cart.items.push(items[i]);
                }
            }
            updateTotalPriceOnPresenter();
        }

        $(".btn-add-to-cart").click(function (e) {

            let t = $(this);
            let loadingText = 'Adding...';
            if (t.html() !== loadingText) {
                t.data('original-text', t.html());
                t.html(loadingText);
                t.addClass("disabled");

                // If item already exist
                let itemInStorage = itemExistInStorage(cart.items, t.data("id"));
                if (!itemInStorage) {
                    let newItem = {
                        id: t.data("id"),
                        name: t.data("name"),
                        price: t.data("price"),
                        amount: 1,
                        addAt: $.now()
                    };
                    cart.items.push(newItem);
                    let key = itemExistInStorage(cart.items, t.data("id"), true);
                    addItemOnPresenter(key, newItem);
                } else {
                    itemInStorage["amount"] = itemInStorage["amount"] + 1;
                }

                t.removeClass("disabled");
                t.html(t.data('original-text'));
            }

            updateLocalStorage();
        });

    });


    function retrieveDetailFromServer(key, item) {
        $.ajax({
            type: 'POST',
            url: 'admin/products.php?action=api',
            dataType: 'json',
            data: cart.items,
            success: function(msg) {
                console.log(msg);
            }
        });
    }


    function addItemOnPresenter(key, item) {
        $("#shopping-list").append(
            '<div class="form-row" data-attribute=' + item.id + '>\n' +
            '    <div class="form-group col-12"><label for="item_' + item.id + '">' + item.name + '</label></div>\n' +
            '    <div class="form-group col-6">\n' +
            '        <input class="form-control" id="item_' + item.id + '" min="0" type="number"\n' +
            '               value="' + item.amount + '"  onchange="onChangeItemAmount(this, ' + key + ', '+ item.id +');">\n' +
            '    </div>\n' +
            '    <div class="form-group col-6 text-right">$'+ item.amount * item.price +'</div>\n' +
            '</div>'
        );
    }


    function itemExistInStorage(items, itemId, itemKey = false) {
        for (let i = 0; i < items.length; i++) {
            if (items[i].id === itemId)
                return (itemKey === true) ? i : items[i];
        }
    }


    function onChangeItemAmount(item, key, id) {
        if(item.value > 0){
            cart.items[key]["amount"] = item.value;
        }else{
            if (confirm('Are you sure to remove the item?')) {
                removeItem(key);
            } else {
                cart.items[key]["amount"] = 1;
            }
        }
        updateLocalStorage();
    }


    function removeItem(key) {
        cart.items.splice(key,1);
        $("#shopping-list").empty();
        let items = cart.items;
        for (let i = 0; i < items.length; i++) {
            if($.isNumeric(items[i].amount) && $.isNumeric(items[i].price)) {
                addItemOnPresenter(i, items[i]);
            }
        }
        updateLocalStorage();
    }


    function updateTotalPriceOnPresenter() {
        let totalPrice = 0;
        $.each(cart.items, function( key, value ) {
            totalPrice += value.amount * value.price;
        });
        $(".shopping-cart-popup-price").html('$ ' + totalPrice);
        $(".shopping-cart-popup-item-amount").html('(' + cart.items.length + ')');
    }


    function updateLocalStorage() {
        localStorage.setItem("shopping_cart", JSON.stringify(cart));
        updateTotalPriceOnPresenter();
    }
</script>


<?php require 'footer.php'; ?>