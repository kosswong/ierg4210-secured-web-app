<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<div class="card shadow-lg my-5">
    <div class="card-body p-0">
        <div class="p-5">
            <div class="text-center">
                <h4 class="text-gray-900 mb-4">Register</h4>
            </div>
            <form class="user" method="post">
                <input type="hidden" name="action" value="register">
                <input type="hidden" name="nonce" value="<?= csrf_getNonce('register') ?>">
                <div class="form-group">
                    <label for="register_email">Email address</label>
                    <input id="register_email" type="email" class="form-control form-control-user" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    <small id="register_email_helper" class="form-text"></small>
                </div>
                <div class="form-group">
                    <label for="register_password">Password</label>
                    <input id="register_password" type="password" class="form-control form-control-user" name="password" placeholder="Password">
                    <small id="register_password_helper" class="form-text"></small>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Confirm</button>
            </form>
        </div>
    </div>
</div>