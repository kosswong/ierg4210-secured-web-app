<?php
require 'inc/config.inc.php';
unset($_SESSION['4210SHOP']);

$get_pid = (isset($_GET['pid']) && is_numeric($_GET['pid']) && ($_GET['pid'] > 0)) ? intval($_GET['pid']) : 1;

$db = DB();
$sql = $db->prepare('SELECT products.*, categories.name as cname FROM products JOIN categories ON products.catid = categories.catid WHERE pid=? LIMIT 1');
$sql->bind_param('i', $get_pid);
$sql->execute();

if ($sql->execute() && $result = $sql->get_result()) {
    if ($row = $result->fetch_assoc()) {
        $pid = intval($row['pid']);
        $catid = intval($row['catid']);
        $name = htmlspecialchars(strip_tags($row['name']));
        $price = floatval($row['price']);
        $description = htmlspecialchars(strip_tags($row['description']));
        $image = urlencode($row['image']);
        $catname = htmlspecialchars(strip_tags($row['cname']));
    }
}

require_header();
?>

<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?catid=<?= $catid?>"><?= $catname?></a></li>
            <li aria-current="page" class="breadcrumb-item active"><?= $name?></li>
        </ol>
    </nav>
</main>

<section class="position-relative bg-light" id="categories">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img alt="..." class="card-img-top" src="img/product/o/<?= $image?>">
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= $name?></h5>
                        <p class="card-text">$<?= $price?></p>
                        <p><?= $description?></p>
                        <form class="form-inline">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="amount">Amount</label>
                                <input class="form-control btn-add-to-cart-main-amount" id="amount" min="1" type="number" value="1">
                            </div>
                            <button type="button" class="btn btn-warning mb-2 btn-add-to-cart" id="item-<?= $pid?>" data-id="<?= $pid?>">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_footer();?>