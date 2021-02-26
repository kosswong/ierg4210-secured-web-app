<?php
require 'header.php';

$link = mysqli_connect("localhost", "root", "", "test");

$catid = isset($_GET['catid']) ? $_GET['catid'] : 1;
$sql = "SELECT * FROM categories WHERE catid='".$_GET["catid"]."' LIMIT 1";// LIMIT 3
if ($result = $link -> query($sql)) {
    while ($row = $result->fetch_row()) {
        $catname = $row[1];
    }
}
?>

    <main class="container">
        <nav aria-label="breadcrumb" id="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li aria-current="page" class="breadcrumb-item active"><?php echo $catname?></li>
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

                        <?php

                        $catid = isset($_GET['catid']) ? $_GET['catid'] : 1;
                        // Check connection
                        if ($link === false) {
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }

                        // Attempt select query execution
                        $sql = "SELECT * FROM categories";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<a class="list-group-item list-group-item-action '.($catid == $row['catid'] ? 'active' : '').'"
                           href="index.php?catid='.$row['catid'].'">'.$row['name'].'</a>';
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
                    </div>
                </div>
                <div class="col-9">
                    <div class="row">
                        <?php
                        $link = mysqli_connect("localhost", "root", "", "test");

                        // Check connection
                        if ($link === false) {
                            die("ERROR: Could not connect. " . mysqli_connect_error());
                        }

                        // Attempt select query execution
                        $sql = "SELECT * FROM products WHERE catid='".$catid."'";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<div class="col-4">';
                                    echo '<div class="card">';
                                    echo '<img alt="Great Item" class="card-img-top" src="img/product/o/1.jpg">';
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . $row['name'] . '</h5>';
                                    echo '<p class="card-text">HK$' . $row['price'] . '</p>';
                                    echo '<a class="btn btn-primary" href="product.php?catid=' . $row['catid'] . '">Detail</a>';
                                    echo '<a class="btn btn-warning" href="#">Add to cart</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
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
                                <a class="btn btn-primary" href="product.php">Detail</a>
                                <a class="btn btn-warning" href="product.php">Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div aria-labelledby="list-d-list" class="tab-pane fade" id="list-d" role="tabpanel">
                    No item
                </div>

            </div>
        </div>
    </section>

<?php require 'footer.php'; ?>