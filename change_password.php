<?php

require 'inc/header.php';


if (isset($_POST['action']) && $_POST['action'] == 'change_password') {

    $sql = $conn->prepare('SELECT admin, userid, email, password, salt FROM users WHERE email=? LIMIT 1');
    $sql->bind_param('s', $email);
    $sql->execute();

    if ($result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            if ($row['password'] == hash_hmac('sha1', $password, $row['salt'])) {

                $exp = time() + 3600 * 24 * 3;
                $token = [
                    'em' => $row['email'],
                    'exp' => $exp,
                    'k' => hash_hmac('sha1', $exp . $row['password'], $row['salt'])
                ];
                setcookie('auth', json_encode($token), $exp, '/', 'localhost', true, true);

                if($row['admin'] == 1) $_SESSION['admin'] = $row['admin'];
                $_SESSION['username'] = $row['email'];
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['auth'] = $token;
                session_regenerate_id();

                $error[] = [
                    "type" => "success",
                    "msg" => "Login successfully.",
                ];

            } else {
                $error[] = [
                    "type" => "danger",
                    "msg" => "Login fail: wrong password.",
                ];
            }
        } else {
            $error[] = [
                "type" => "warning",
                "msg" => "Login fail: no related record.",
            ];
        }
    }

}

?>
    <form class="form-signin" method="post">
        <input type="hidden" name="action" value="change_password">
        <h1 class="h3 mb-3 font-weight-normal">Change password</h1>
        <label for="inputEmail" class="sr-only">Current password</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Old password" required autofocus>
        <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="New Password" required>
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