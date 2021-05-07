<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<h1 class="h3 mb-2 text-gray-800">Categories</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit category</h6>
    </div>
    <div class="card-body">
        <form class="user" action="../../admin/categories.php" method="post">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="nonce" value="<?= csrf_getNonce('admin_category_save') ?>">
            <input type="hidden" name="catid" value="<?= $catid ?>">
            <div class="form-group">Category ID
                <input type="text" class="form-control" value="<?= $catid ?>" disabled>
            </div>
            <div class="form-group">Category Name
                <input type="text" class="form-control" value="<?= $name ?>" name="name" placeholder="Category Name">
            </div>
            <div class="form-group">Alternative Name
                <input type="text" class="form-control" value="<?= $cname ?>" name="cname" placeholder="Category Alternative Name">
            </div>
            <input class="btn btn-primary btn-user btn-block" type="submit" value="Edit" name="submit">
        </form>
    </div>
</div>