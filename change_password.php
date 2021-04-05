<?php

require 'inc/header.php';
$error = [];
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    $error[] = ["type" => "danger", "msg" => "We are sorry that the service currently not available.",];
}

if (isset($_POST['action']) && $_POST['action'] == 'change_password') {

}



?>
    <form class="form-signin" method="post">
        <input type="hidden" name="action" value="change_password">
        <h1 class="h3 mb-3 font-weight-normal">Change password</h1>
        <label for="inputEmail" class="sr-only">Current password</label>
        <input type="password" name="password" id="inputEmail" class="form-control" placeholder="Old password" required autofocus>
        <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" name="password_new" id="inputPassword" class="form-control" placeholder="New Password" required>
        <?php if (isset($error)) {
            foreach ($error as $value) { ?>
                <div class="alert alert-<?= $value["type"] ?>" role="alert">
                    <?= $value["msg"] ?>
                </div>
            <?php }
        } ?>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Go</button>
    </form>
<?php require 'inc/footer.php'; ?>