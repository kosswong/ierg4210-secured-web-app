<div class="container">
    <form method="post">
        <input type="hidden" name="action" value="register">
        <input type="hidden" name="nonce" value="<?= csrf_getNonce('register') ?>">
        <h1 class="h3 mb-3 font-weight-normal">Register</h1>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" class="form-control" aria-describedby="emailHelp"
                   placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>        <?php if (isset($error)) {
            foreach ($error as $value) { ?>
                <div class="alert alert-<?= $value["type"] ?>" role="alert">
                    <?= $value["msg"] ?>
                </div>
            <?php }
        } ?>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>