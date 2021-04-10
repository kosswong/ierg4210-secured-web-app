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
                <h4 class="text-gray-900 mb-4">Sign in</h4>
            </div>
            <form class="user" method="post">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="nonce" value="<?= csrf_getNonce('login') ?>">
                <div class="form-group">
                    <label for="login_email">Email address</label>
                    <input id="login_email"  type="email" class="form-control form-control-user" name="email" aria-describedby="emailHelp" placeholder="Enter Email Address..." value="test@test.com">
                </div>
                <div class="form-group">
                    <label for="login_password">Password</label>
                    <input id="login_password" type="password" class="form-control form-control-user" name="password" placeholder="Password" value="aaaaAAAA1111">
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
            </form>
        </div>
    </div>
</div>