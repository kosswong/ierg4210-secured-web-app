<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>

<form method="post">
    <input type="hidden" name="action" value="login">
    <input type="hidden" name="nonce" value="<?= csrf_getNonce('login') ?>">
    <h1 class="h3 mb-3 font-weight-normal">Sign in</h1>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" id="login_email" name="email" class="form-control" aria-describedby="emailHelp"
               placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" id="login_password" name="password" class="form-control" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>