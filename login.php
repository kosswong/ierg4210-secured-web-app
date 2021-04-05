<?php require 'inc/header.php'; ?>

<?php
// Connect mySQL
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$cat_list = '';     //Categories list
$p_list = '';       //Product list

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
                                . '<button type="button" class="btn btn-warning btn-add-to-cart" id="item-' . $product['pid'] . '" data-id="' . $product['pid'] . '">
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

    <form class="form-signin" method="post">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <div class="alert alert-danger" role="alert">
            Invalid Input.
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

<?php require 'inc/footer.php'; ?>