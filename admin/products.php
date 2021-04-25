<?php
defined('IERG4210ADMIN') or define('IERG4210ADMIN', true);
require '../inc/config.inc.php';
if (auth_admin() == false) {
    header("Location: http://localhost");
    die();
}

$setting = 'products';
$pid = '';
$catid = '';
$name = '';
$price = '';
$description = '';
$categories = [];
$cat_list = '';
function product_main()
{
    $db = DB();
    $sql = "SELECT * FROM products";// LIMIT 3
    if ($products = mysqli_query($db, $sql)) {
        $categories = get_category();
        $count = mysqli_num_rows($products);
        require 'view/product_main.php';
        mysqli_free_result($products);
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
    }
}

function product_add()
{
    $categories = get_category();
    require 'view/product_add.php';
    require 'view/footer.php';
}

function product_edit($pid)
{
    $db = DB();
    $sql = $db->prepare('SELECT * FROM products WHERE pid=? LIMIT 1');
    $sql->bind_param('i', $pid);
    if ($sql->execute() && $result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            $categories = get_category();
            require 'view/product_edit.php';
            $result->free_result();
        }
    } else {
        echo "Error: " . $db->error;
    }
    require 'view/footer.php';
}

function product_save($pid, $nonce)
{
    try {
        if (csrf_verifyNonce('admin_product_save', $nonce) == true) {

            $catid = intval($_POST["catid"]);
            $name = strip_tags($_POST["name"]);
            $price = strip_tags($_POST["price"]);
            $description = strip_tags($_POST["description"]);

            $db = DB();
            $sql = $db->prepare("UPDATE products SET name=?, catid=?, price=?, description=? WHERE pid=?");
            $sql->bind_param('sidsi', $name, $catid, $price, $description, $pid);
            if ($sql->execute() === TRUE) {
                $message_type = 'success';
                $message = "Record updated successfully.";
                if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
                    $imageFileType = uploadImage($pid);
                    if ($imageFileType) {
                        $sql = "UPDATE products SET image='" . $pid . '.' . $imageFileType . "' WHERE pid='" . $pid . "'";
                        if ($db->query($sql) === TRUE) {
                            $message .= " Image updated successfully";
                        }
                    }
                }
            } else {
                $message_type = 'danger';
                $message = "Error updating record: " . $db->error;
            }
        } else {
            $message_type = 'warning';
            $message = "Invalid operation.";
        }
    } catch (Exception $e) {
        $message_type = 'warning';
        $message = "Bad session.";
    }
    require 'view/message.php';
}

function product_delete($pid)
{
    $db = DB();
    $sql = $db->prepare("DELETE FROM `products` WHERE pid=?");
    $sql->bind_param('i', $pid);
    if ($sql->execute() === TRUE) {
        $message_type = 'success';
        $message = "Deleted!";
    } else {
        $message_type = 'danger';
        $message = "Error updating record: " . $db->error;
    }
    require 'view/message.php';
}

function product_add_confirm($catid, $name, $price, $description)
{
    $catid = intval($catid);
    $name = strip_tags($name);
    $price = floatval($price);
    $description = strip_tags($description);

    $db = DB();
    $sql = $db->prepare("INSERT INTO products (pid, catid, name, price, description) VALUES (NULL, ?, ?, ?, ?)");
    $sql->bind_param('isds', $catid, $name, $price, $description);
    if ($sql->execute() === TRUE) {
        $insert_id = $sql->insert_id;
        $message = "New record created successfully";
        if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
            $imageFileType = uploadImage($insert_id);
            if ($imageFileType) {
                $sql = "UPDATE products SET image='" . $insert_id . '.' . $imageFileType . "' WHERE pid='" . $insert_id . "'";
                if ($db->query($sql) === TRUE) {
                    $message .= "Record created successfully";
                }
            }
        }
    } else {
        $message = "Error: " . $sql . "<br>" . $db->error;
    }
    require 'view/message.php';
}

function get_category()
{
    $db = DB();
    $sql = "SELECT * FROM categories";
    $categories = [];
    if ($result = mysqli_query($db, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $categories[$row['catid']] = strip_tags($row['name']);
            }
            mysqli_free_result($result); // Close result set
        }
        return $categories;
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
    }
}

require 'view/header.php';
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'add':
            if (isset($_POST["catid"]) && isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["description"])) {
                product_add_confirm($_POST["catid"], $_POST["name"], $_POST["price"], $_POST["description"]);
            } else {
                product_add();
            }
            exit;
        case 'edit':
            if (isset($_GET["pid"])) {
                product_edit($_GET["pid"]);
            }
        case 'save':
            if (isset($_POST["action"]) && isset($_POST["pid"])) {
                product_save($_POST["pid"], $_POST["nonce"]);
            }
            exit;
        case 'delete':
            if (isset($_GET["pid"])) {
                product_delete($_GET["pid"]);
            }
            exit;
        default:
            require_full_page('inc/error.php');
    }
} else {
    product_main();
}
require 'view/footer.php';
