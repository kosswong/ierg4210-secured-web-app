<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<form method="post">
    <input type="hidden" name="action" value="password">
    <input type="hidden" name="nonce" value="<?= csrf_getNonce('password') ?>">
    <h1 class="h3 mb-3 font-weight-normal">Change password</h1>
    <label for="inputEmail" class="sr-only">Current password</label>
    <input type="password" id="change_password" name="password" class="form-control" placeholder="Old password" required
           autofocus>
    <label for="inputPassword" class="sr-only">New Password</label>
    <input type="password" id="change_password_new" name="password_new" class="form-control" placeholder="New Password"
           required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Go</button>
</form>