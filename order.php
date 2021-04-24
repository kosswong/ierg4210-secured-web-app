<?php
require 'inc/config.inc.php';
unset($_SESSION['4210SHOP']);

$email = isset($_SESSION['email']) ? filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL) : 'Guest';
$get_pid = (isset($_GET['pid']) && is_numeric($_GET['pid']) && ($_GET['pid'] > 0)) ? intval($_GET['pid']) : 1;

// My order
$db = DB();
$sql = $db->prepare('SELECT * FROM orders WHERE username=? order by id desc LIMIT 5');
$sql->bind_param('s', $email);
if ($sql->execute()) {
    $count = 0;
    $result = $sql->get_result();
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
}

// Product list
$sql_products = $db->prepare('SELECT pid, name FROM products');
$products_list = [];
if ($sql_products->execute()) {
    $product_result = $sql_products->get_result();
    while ($row = $product_result->fetch_assoc()) {
        $products_list[$row["pid"]] = $row["name"];
    }
} else {
    echo "ERROR: Could not able to execute $sql_products. " . mysqli_error($db);
}

require_header();
?>

    <main class="container">
        <nav aria-label="breadcrumb" id="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li aria-current="page" class="breadcrumb-item active">My last 5 orders</li>
            </ol>
        </nav>
    </main>

    <section class="position-relative bg-light" id="categories">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <?php if (isset($result)) {
                        while ($row = $result->fetch_assoc()) {
                            $count++;
                            ?>
                            <div class="card mb-4 py-3 border-bottom-<?= $row['txn_id'] ? 'success' : 'danger' ?>">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <?php
                                            $items = json_decode($row['cart'], true);
                                            $items = is_array($items) ? $items : [];
                                            foreach ($items as &$item) {
                                                $name = isset($item["quantity"]) && isset($products_list) ? $products_list[$item["pid"]] : "N/A";
                                                $quantity = isset($item["quantity"]) ? $item["quantity"] : "N/A";
                                                $price = isset($item["price"]) ? $item["price"] : "N/A";
                                                ?>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                            <?= $name ?> x <?= $quantity ?>
                                                        </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            $<?= $price ?></div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-auto">
                                            <?= $row['txn_id'] ? '<span class="btn btn-success btn-circle btn-sm"><i class="fas fa-check fa-2x"></i></span>' : 'Failed' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    }
                    if ($count == 0) {
                        echo "No records matching your query were found.";
                    } ?>
                </div>
            </div>
        </div>
    </section>

<?php require_footer(); ?>