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
                    <form action="http://www.paypal.com" method="POST">
                        <div class="container shopping-list" id="shopping-list">
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
                                <div class="form-group col-6">Sum (Estimate):</div>
                                <div class="form-group col-6 text-right">$82.1 </div>
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
                                . '<a class="btn btn-warning" href="#">Add to cart</a>'
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

<div class="container">
    <div class="row">
        <div class="col-xs-3">
            <div class="well item-1-container">
                <p class="lead">
                    Item 1
                </p>
                <button class="btn btn-primary" id="item-1" data-id="item-1">+</button>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="well item-2-container">
                <p class="lead">
                    Item 2
                </p>
                <button class="btn btn-primary" id="item-2" data-id="item-2">+</button>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="well item-3-container">
                <p class="lead">
                    Item 3
                </p>
                <button class="btn btn-primary" id="item-3" data-id="item-3">+</button>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="well item-4-container">
                <p class="lead">
                    Item 4
                </p>
                <button class="btn btn-primary" id="item-4" data-id="item-4">+</button>
            </div>
        </div>
    </div>
</div>

<hr/>

<div class="container">
    <h3>The List</h3>
    <ul class="list-unstyled list-inline"></ul>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script>
    let cart = {};
    $(document).ready(function () {

        // Check local data, it nothing exist, then create
        if (localStorage.getItem("shopping_cart") != null) {
            let items = JSON.parse(localStorage.getItem("shopping_cart")).items;
            for (let i = 0; i < items.length; i++) {
                addItem(items[i]);
                cart.items.push(items[i]);
            }
            setBadge();
        }

        $(".btn-primary").click(function (e) {
            $(this).toggleClass("btn-warning");
            toggleText($(this).attr('id'));

            if (!$(`li[data-attribute="${e.target.id}"]`).length) {
                addItem(e.target.id);
            } else {
                $(`li[data-attribute="${e.target.id}"]`).remove();
            }
            setBadge();
            setLocalStorage(e.target.id, 0);
            $(".btn-danger").on('click', function () {
                removeItem($(this).parent("li"));
                setLocalStorage($(this).data('id'), 1);
            });
        });

        $(".btn-danger").on('click', function () {
            removeItem($(this).parent("li"));
            setLocalStorage($(this).data('id'), 1);
        });
    });

    function removeItem(item) {
        console.log(item);
        var id = item[0].attributes[1].value;
        $(`#${id}`).removeClass("btn-warning");
        toggleText(id);
        item.remove();
        setBadge();
    }

    function addItem(item) {
        $("ul").append(
            `<li class='well' data-attribute='${item}'>
${$(`.${item}-container p`).text()}
<button class='btn btn-danger' data-id='${item}'>-</button>
</li>`
        );
    }

    function setBadge() {
        $(".badge").remove();
        $("h3").after("<span class='badge'>" + $("li").length + "</span>");
    }

    function toggleText(item) {
        if ($('#' + item).hasClass("btn-warning")) {
            $('#' + item).text("-");
        } else {
            $('#' + item).text("+");
        }
    }


    cart.items = [];

    function setLocalStorage(id, flag) {
        if (flag) {
            cart.items.splice(cart.items.indexOf(id), 1);
        } else {
            cart.items.push(id);
        }
        console.log(JSON.stringify(cart));
        localStorage.setItem("shopping_cart", JSON.stringify(cart));
    }
</script>


<?php require 'footer.php'; ?>