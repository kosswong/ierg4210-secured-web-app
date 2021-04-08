<?php
require 'inc/config.inc.php';
require_header();

// Numeric check
$pid = (isset($_GET['pid']) && is_numeric($_GET['pid']) && ($_GET['pid'] > 0)) ? $_GET['pid'] : 1;

// Prevent SQL injection
$db = DB();
$sql = $db->prepare('SELECT products.*, categories.name as cname FROM products JOIN categories ON products.catid = categories.catid WHERE pid=? LIMIT 1');
$sql->bind_param('i', $pid); // 'i' specifies the variable type => 'integer'
$sql->execute();

if ($result = $sql->get_result()) {
    while ($row = $result->fetch_row()) {
        $pid = $row[0];
        $catid = $row[1];
        $name = $row[2];
        $price = $row[3];
        $description = $row[4];
        $image = $row[8];
        $catname = $row[9];
    }
}
?>

<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php?catid=<?php echo $catid?>"><?php echo $catname?></a></li>
            <li aria-current="page" class="breadcrumb-item active"><?php echo $name?></li>
        </ol>
    </nav>
</main>


<section class="position-relative bg-light" id="categories">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img alt="..." class="card-img-top" src="img/product/o/<?php echo $image?>">
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $name?></h5>
                        <p class="card-text">$<?php echo $price?></p>
                        <p><?php echo $description?></p>
                        <form class="form-inline">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="amount">Amount</label>
                                <input class="form-control btn-add-to-cart-main-amount" id="amount" min="0" type="number" value="1">
                            </div>
                            <button type="button" class="btn btn-warning mb-2 btn-add-to-cart" id="item-<?php echo $pid?>" data-id="<?php echo $pid?>">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'inc/footer.php'; ?>