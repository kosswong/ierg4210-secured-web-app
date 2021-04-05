<?php

require 'inc/header.php';
$error = [];
$conn = mysqli_connect("localhost", "root", "", "test");
if ($conn === false) {
    $error[] = ["type" => "danger", "msg" => "We are sorry that the service currently not available.",];
}

if (isset($_POST['action']) && $_POST['action'] == 'change_password') {

    $password = $_POST['password'];
    $password_new = $_POST['password_new'];

    $sql = $conn->prepare('SELECT password, salt FROM users WHERE email=? LIMIT 1');
    $sql->bind_param('s', $_SESSION['username']);
    $sql->execute();

    if ($result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            if ($row['password'] == hash_hmac('sha1', $password, $row['salt'])) {

                try {
                    $salt = bin2hex(random_bytes(8));
                } catch (Exception $e) {
                    $salt = bin2hex("IERG4210");
                    $error[] = [
                        "type" => "server",
                        "msg" => "Cannot use random_bytes() function, used without it.",
                    ];
                }

                $password_new = hash_hmac('sha1', $_POST['password_new'], $salt);

                $sql = "UPDATE users SET password='$password_new', salt='$salt' WHERE email='{$_SESSION['username']}'";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

                $error[] = [
                    "type" => "success",
                    "msg" => "Change successfully.",
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
        <input type="password" name="password" id="inputEmail" class="form-control" placeholder="Old password" required autofocus>
        <label for="inputPassword" class="sr-only">New Password</label>
        <input type="password" name="password_new" id="inputPassword" class="form-control" placeholder="New Password" required>
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