<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<h1 class="h3 mb-2 text-gray-800">Products</h1>
<p class="mb-4">It is the product list.</p>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Product data</h6>
        <a href="products.php?action=add" role="button">Add product</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                <tr>
                    <th>PID</th>
                    <th>Category</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Operation</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($products) && isset($categories) && isset($count) && $count > 0) {
                    while ($row = mysqli_fetch_array($products)) {
                        ?>
                        <tr>
                            <td><?= intval($row['pid']) ?></td>
                            <td><?= isset($categories[$row['catid']]) ? htmlspecialchars(strip_tags($categories[$row['catid']])) : 'N/A' ?></td>
                            <td><?= htmlspecialchars(strip_tags($row['name'])) ?></td>
                            <td><img src="../img/product/s/<?= urlencode($row['image']) ?>" class="product-thumb"></td>
                            <td><?= floatval($row['price']) ?></td>
                            <td><?= htmlspecialchars(strip_tags($row['description'])) ?></td>
                            <td>
                                <a href="products.php?action=edit&pid=<?= intval($row['pid']) ?>">Edit</a>
                                <a href="products.php?action=delete&pid=<?= intval($row['pid']) ?>">Delete</a>
                            </td>
                        </tr>
                    <?php }
                }else{
                    echo "<tr><td colspan='7'>No records matching your query were found.</td></tr>";
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>