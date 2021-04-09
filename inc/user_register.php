<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>

<form method="post" id="register_form">
    <input type="hidden" name="action" value="register">
    <input type="hidden" name="nonce" value="<?= csrf_getNonce('register') ?>">
    <h1 class="h3 mb-3 font-weight-normal">Register</h1>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input id="register_email" type="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="register_email_helper" class="form-text"></small>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input id="register_password" type="password" name="password" class="form-control" placeholder="Password" onchange="validateRegisterPassword()">
        <small id="register_password_helper" class="form-text"></small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>