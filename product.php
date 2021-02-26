<?php
require 'header.php';

$link = mysqli_connect("localhost", "root", "", "test");

$pid = isset($_GET['pid']) ? $_GET['pid'] : 1;
$sql = "SELECT products.*, categories.name as cname FROM products JOIN categories ON products.catid = categories.catid WHERE pid='".$pid."' LIMIT 1 ";// LIMIT 3
if ($result = $link -> query($sql)) {
    while ($row = $result->fetch_row()) {
        $pid = $row[0];
        $catid = $row[1];
        $name = $row[2];
        $price = $row[3];
        $description = $row[4];
        $catname = $row[5];
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
                <img alt="..." class="card-img-top" src="img/product/o/<?php echo $pid?>.jpg">
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
                                <select class="form-control" id="amount">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning mb-2">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>