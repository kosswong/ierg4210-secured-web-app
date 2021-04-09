<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<h1 class="h3 mb-2 text-gray-800">Category</h1>
<p class="mb-4">It is the category list.</p>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Category data</h6>
        <a href="../../admin/categories.php?action=add" role="button">Add category</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                <tr>
                    <th>Cat ID</th>
                    <th>Category Name</th>
                    <th>Alternative Name</th>
                    <th>Operation</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($categories)) {
                    while ($row = mysqli_fetch_array($categories)) {
                        ?>
                        <tr>
                            <td><?= $row['catid'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['cname'] ?></td>
                            <td>
                                <a href="../../admin/categories.php?action=edit&catid=<?= $row['catid'] ?>">Edit</a>
                                <a href="../../admin/categories.php?action=del&catid=<?= $row['catid'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>