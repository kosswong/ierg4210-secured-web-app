<?php
$error = isset($error) ? $error : [];
?>
<div class="container">
    <form method="post">
        <input type="hidden" name="action" value="change_password">
        <input type="hidden" name="nonce" value="<?= csrf_getNonce('change_password') ?>">
        <h1 class="h3 mb-3 font-weight-normal">Change password</h1>
        <label for="inputEmail" class="sr-only">Current password</label>
        <input type="password" name="password" id="inputEmail" class="form-control" placeholder="Old password" required
               autofocus>
        <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" name="password_new" id="inputPassword" class="form-control" placeholder="New Password"
               required>
        <?php if (isset($error)) {
            foreach ($error as $value) { ?>
                <div class="alert alert-<?= $value["type"] ?>" role="alert">
                    <?= $value["msg"] ?>
                </div>
            <?php }
        } ?>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Go</button>
    </form>
</div>