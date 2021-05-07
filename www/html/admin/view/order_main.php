<?php
if (!defined('IERG4210ADMIN')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<h1 class="h3 mb-2 text-gray-800">Order</h1>
<p class="mb-4">It is the order list.</p>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Order data</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>User</th>
                    <th>Products & Prices</th>
                    <th>Payment Finished?</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($orders) && isset($count) && $count > 0) {
                    while ($row = mysqli_fetch_array($orders)) {
                        ?>
                        <tr>
                            <td><?= intval($row['id']) ?></td>
                            <td><?= htmlspecialchars(strip_tags($row['username'])) ?></td>
                            <td>
                                <?php
                                $items = json_decode($row['cart'], true);
                                $items = is_array($items) ? $items : [];
                                foreach ($items as &$item) {
                                    // Sanitizer
                                    $name = isset($item["quantity"]) && isset($products_list) ? htmlspecialchars(strip_tags($products_list[$item["pid"]])) : "N/A";
                                    $quantity = isset($item["quantity"]) ? intval($item["quantity"]) : "N/A";
                                    $price = isset($item["price"]) ? floatval($item["price"]) : "N/A";
                                    ?>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <?= $name ?> x <?= $quantity ?>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?= $price ?></div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </td>
                            <td><?= $row['txn_id'] ? '<span class="btn btn-success btn-circle btn-sm">
                                    <i class="fas fa-check"></i>
                                </span><br>' . htmlspecialchars(strip_tags($row['txn_id'])) : "No" ?></td>
                        </tr>
                    <?php }
                } else {
                    echo "<tr><td colspan='4'>No records matching your query were found.</td></tr>";
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>