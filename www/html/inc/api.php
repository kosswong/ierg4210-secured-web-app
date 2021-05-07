<?php
require 'config.inc.php';
header('Content-Type: application/json');

function validate_cart($id)
{
    $db = DB();
    $sql = "SELECT * FROM products WHERE pid='$id' LIMIT 1";
    if ($result = $db->query($sql)) {
        while ($row = $result->fetch_row()) {
            echo json_encode(array('id' => $row[0], 'name' => $row[2], 'price' => $row[3]));
        }
        $result->free_result();
    }
}

function validate_register_email($email)
{
    if(validateEmail($email) === false){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('success' => false, 'message' => validateEmail($email)));
    }
}

function validate_register_password($password)
{
    if(validatePassword($password) === false){
        echo json_encode(array('success' => true));
    }else{
        echo json_encode(array('success' => false, 'message' => validatePassword($password)));
    }
}

function error_json_message($message = 'Invalid operation.')
{
    echo json_encode(array('success' => false, 'msg' => $message, 'your_ip' => get_ip()));
    exit;
}

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'validate_cart':
            if (isset($_POST['id'])) {
                validate_cart($_POST['id']);
            } else {
                error_json_message();
            }
            break;
        case 'validate_register_email':
            if (isset($_POST['email'])) {
                validate_register_email($_POST['email']);
            } else {
                error_json_message();
            }
            break;
        case 'validate_register_password':
            if (isset($_POST['password'])) {
                validate_register_password($_POST['password']);
            } else {
                error_json_message();
            }
            break;
        default:
            error_json_message();
    }
} else {
    error_json_message();
}

