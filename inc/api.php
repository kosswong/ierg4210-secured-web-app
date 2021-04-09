<?php
require 'config.inc.php';


function validate_cart()
{
    if (isset($_POST['id'])) {
        $db = DB();
        header('Content-Type: application/json');
        $sql = "SELECT * FROM products WHERE pid='" . $_POST['id'] . "' LIMIT 1";// LIMIT 3
        if ($result = $db->query($sql)) {
            while ($row = $result->fetch_row()) {
                echo json_encode(array('id' => $row[0], 'name' => $row[2], 'price' => $row[3]));
            }
            $result->free_result();
        }
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(array('msg' => 'Error!'));
        exit;
    }
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'validate_cart':
            validate_cart();
            break;
        case 'register':
            if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                user_register($_POST['email'], $_POST['password'], $_POST['nonce']);
            }
            require_full_page('inc/user_register.php');
            break;
        case 'login':
            if (isset($_POST['action']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nonce'])) {
                user_login($_POST['email'], $_POST['password'], $_POST['nonce']);
            }
            require_full_page('inc/user_login.php');
            break;
        case 'password':
            if (isset($_POST['action']) && isset($_POST['password']) && isset($_POST['password_new']) && isset($_POST['nonce'])) {
                user_change_password($_POST['password'], $_POST['password_new'], $_POST['nonce']);
            }
            require_full_page('inc/user_password.php');
            break;
        default:
            require_full_page('inc/error.php');
    }
} else {
    require_full_page('inc/error.php');
}

?>