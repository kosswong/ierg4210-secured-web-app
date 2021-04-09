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

function error_json_message($message = 'Invalid operation.')
{
    echo json_encode(array('msg' => $message, 'your_ip' => get_ip()));
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
        default:
            error_json_message();
    }
    exit;
} else {
    error_json_message();
    exit;
}

?>