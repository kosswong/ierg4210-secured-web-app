<?php
require 'header.php';

$link = mysqli_connect("localhost", "root", "", "test");

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$current_catid = isset($_GET['catid']) ? $_GET['catid'] : 1;
$current_catname = '';

$cat_list = ''; //Categories list
$p_list = ''; //Product list

// Attempt select query execution
$sql = "SELECT * FROM categories";
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if ($current_catid == $row['catid']) {
                $current_catname = $row['name'];

                // Attempt select query execution
                $sql_product = "SELECT * FROM products WHERE catid='" . $current_catid . "'";
                if ($result_product = mysqli_query($link, $sql_product)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $p_list = '<div class="col-4">'
                                .'<div class="card">'
                                .'<img alt="Great Item" class="card-img-top" src="img/product/o/' . $row['catid'] . '.jpg">'
                                .'<div class="card-body">'
                                .'<h5 class="card-title">' . $row['name'] . '</h5>'
                                .'<p class="card-text">HK$' . $row['price'] . '</p>'
                                .'<a class="btn btn-primary" href="product.php?pid=' . $row['catid'] . '">Detail</a>'
                                .'<a class="btn btn-warning" href="#">Add to cart</a>'
                                .'</div>'
                                .'</div>'
                                .'</div>';
                        }
                        // Close result set
                        mysqli_free_result($result);
                    } else {
                        echo "No records matching your query were found.";
                    }
                } else {
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }

            }
            $cat_list .= '<a class="list-group-item list-group-item-action ' . ($current_catid == $row['catid'] ? 'active' : '') . '"
                           href="index.php?catid=' . $row['catid'] . '">' . $row['name'] . '</a>';
        }
        // Close result set
        mysqli_free_result($result);
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);

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

<?php require 'footer.php'; ?>