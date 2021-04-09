<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>

<form method="post" id="password_form">
    <input type="hidden" name="action" value="password">
    <input type="hidden" name="nonce" value="<?= csrf_getNonce('password') ?>">
    <h1 class="h3 mb-3 font-weight-normal">Change password</h1>
    <div class="form-group">
        <label for="exampleInputEmail1">Password</label>
        <input id="change_password" type="password" name="password" class="form-control" placeholder="Password" onchange="validateChangePassword()">
        <small id="change_password_helper" class="form-text"></small>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">New Password</label>
        <input id="change_password_new" type="password_new" name="password_new" class="form-control" placeholder="Password" onchange="validateChangePasswordNew()">
        <small id="change_password_new_helper" class="form-text"></small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>