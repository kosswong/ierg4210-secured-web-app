<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<h1 class="h3 mb-2 text-gray-800">Categories</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add new category</h6>
    </div>
    <div class="card-body">
        <form class="user" action="../../admin/categories.php" method="post">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="nonce" value="<?= csrf_getNonce('admin_category_add') ?>">
            <div class="form-group">Categories Name
                <input type="text" class="form-control" name="name" placeholder="Name">
            </div>
            <div class="form-group">Categories Alternative Name
                <input type="text" class="form-control" name="cname" placeholder="Alternative Name">
            </div>
            <input class="btn btn-primary btn-user btn-block" type="submit" value="Add" name="submit">
        </form>
    </div>
</div>