<?php
require 'inc/config.inc.php';
unset($_SESSION['4210SHOP']);

$get_cat_id = (isset($_GET['catid']) && is_numeric($_GET['catid']) && ($_GET['catid'] > 0)) ? intval($_GET['catid']) : 1;

function get_cat_name($cat_id)
{
    $cat_id = intval($cat_id);
    $db = DB();
    $sql = $db->prepare('SELECT * FROM categories WHERE catid=? LIMIT 1');
    $sql->bind_param('i', $cat_id);
    if ($sql->execute() && $result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            return $row['name'];
        } else {
            return '';
        }
    }
}

function get_cat_list()
{
    $db = DB();
    $sql = "SELECT * FROM categories";
    if ($categories = mysqli_query($db, $sql)) {
        return $categories;
    } else {
        return false;
    }
}

function get_product_list($cat_id)
{
    $cat_id = intval($cat_id);
    $db = DB();
    $sql = $db->prepare('SELECT * FROM products WHERE catid=?');
    $sql->bind_param('i', $cat_id);
    if ($sql->execute() && $result = $sql->get_result()) {
        return $result;
    } else {
        return false;
    }
}

require_header();
?>

    <main class="container">
        <nav aria-label="breadcrumb" id="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active"><?= get_cat_name($get_cat_id) ?></li>
            </ol>
        </nav>
        <div class="card mb-4 py-3 border-left-primary shadow-sm">
            <div class="card-body">
                <h4>Shop Heath, It'll blow your mind. </h4>
                <p class="mb-0 small">“Health is a state of complete harmony of the body, mind and spirit. When one is
                    free from physical disabilities and mental distractions, the gates of the soul open.” – B.K.S. Iyengar</p>
            </div>
        </div>
    </main>

    <section class="position-relative bg-light" id="categories">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="list-group" id="list-tab" role="tablist">
                        <?php if ($categories = get_cat_list()) {
                            if (mysqli_num_rows($categories) > 0) {
                                foreach ($categories as $row) {
                                    $cat_id = intval($row['catid']);
                                    $cat_name = htmlspecialchars(strip_tags($row['name']));
                                    $active = $get_cat_id == $row["catid"] ? " active" : '';
                                    echo '<a class="list-group-item list-group-item-action' . $active . '" href="index.php?catid=' . $cat_id . '">'
                                        . $cat_name
                                        . '</a>';
                                }
                            } else {
                                echo 'No category.';
                            }
                        } ?>
                    </div>
                </div>
                <div class="col-12 col-md-9">
                    <div class="row">
                        <?php if ($products = get_product_list($get_cat_id)) {
                            if (mysqli_num_rows($products) > 0) {
                                foreach ($products as $row) {
                                    $image = urlencode($row['image']);
                                    $name = htmlspecialchars(strip_tags($row['name']));
                                    $price = floatval($row['price']);
                                    $pid = intval($row['pid']);
                                    echo '<div class="col-md-4 col-sm-12">'
                                        . '<div class="card">'
                                        . '<a href="product.php?pid=' . $pid . '"><img alt="' . $name . '" class="card-img-top" src="img/product/s/' . $image . '"></a>'
                                        . '<div class="card-body">'
                                        . '<a href="product.php?pid=' . $pid . '"><h5 class="card-title">' . $name . '</h5></a>'
                                        . '<p class="card-text">HK$' . $price . '</p>'
                                        . '<a class="btn btn-primary" href="product.php?pid=' . $pid . '">Detail</a>'
                                        . '<button type="button" class="btn btn-warning btn-add-to-cart ml-lg-3" id="item-' . $pid . '" data-id="' . $pid . '">Add to cart</button>'
                                        . '</div>'
                                        . '</div>'
                                        . '</div>';
                                }
                            } else {
                                echo 'No record.';
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php require_footer(); ?>