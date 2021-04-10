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
                <h4 class="text-gray-900 mb-4">Change password</h4>
            </div>
            <form class="user" method="post">
                <input type="hidden" name="action" value="password">
                <input type="hidden" name="nonce" value="<?= csrf_getNonce('password') ?>">
                <div class="form-group">
                    <label for="change_password">Password</label>
                    <input id="change_password" type="password" class="form-control form-control-user" name="password" placeholder="Enter your current password...">
                    <small id="change_password_helper" class="form-text"></small>
                </div>
                <div class="form-group">
                    <label for="change_password_new">New Password</label>
                    <input id="change_password_new" type="password" class="form-control form-control-user" name="password_new" placeholder="Enter your new password...">
                    <small id="change_password_new_helper" class="form-text"></small>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Change and Logout</button>
            </form>
        </div>
    </div>
</div>