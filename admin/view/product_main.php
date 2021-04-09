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
                <?php if (isset($products) && isset($categories)) {
                    while ($row = mysqli_fetch_array($products)) {
                        ?>
                        <tr>
                            <td><?= $row['pid'] ?></td>
                            <td><?= isset($categories[$row['catid']]) ? $categories[$row['catid']] : 'N/A' ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><img src="../img/product/s/<?= $row['image'] ?>" class="product-thumb"></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td>
                                <a href="products.php?action=edit&pid=<?= $row['pid'] ?>">Edit</a>
                                <a href="products.php?action=delete&pid=<?= $row['pid'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>