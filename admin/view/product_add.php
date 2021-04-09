<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<h1 class="h3 mb-2 text-gray-800">Products</h1>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Add new product</h6>
    </div>
    <div class="card-body">
        <form class="user" action="products.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="nonce" value="<?= csrf_getNonce('admin_product_add') ?>">
            <div class="form-group">Product Name
                <input type="text" class="form-control" id="product_name" name="name" placeholder="Product Name">
            </div>
            <div class="form-group row">
                <div class="col-sm-6">Product Price
                    <input type="number" min="0.00" max="10000.00" step="0.01" class="form-control" id="product_price"
                           name="price" placeholder="(e.g 30.2)">
                </div>
                <div class="col-sm-6">Category
                    <select class="form-control" name="catid" id="catid">
                        <?php if (isset($categories)) {
                            foreach ($categories as $key => $val) { ?>
                                <option value="<?= $key ?>"><?= $val ?></option>
                            <?php }
                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">Description
                <textarea class="form-control" id="product_description" name="description" rows="4"
                          cols="50"></textarea>
            </div>
            <div class="form-group">Image
                <input type="file" class="form-control" id="product_image" name="image"
                       placeholder="Select image to upload">
            </div>
            <input class="btn btn-primary btn-user btn-block" type="submit" value="Add" name="submit">
        </form>
    </div>
</div>