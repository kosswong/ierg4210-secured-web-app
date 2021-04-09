<?php
defined('IERG4210ADMIN') or define('IERG4210ADMIN', true);
require '../inc/config.inc.php';
if (auth_admin() == false) {
    header("Location: http://localhost");
    die();
}

$setting = 'categories';
function category_main()
{
    $db = DB();
    $sql = "SELECT * FROM categories";// LIMIT 3
    if ($categories = mysqli_query($db, $sql)) {
        if (mysqli_num_rows($categories) > 0) {
            require 'view/category_main.php';
            mysqli_free_result($categories);
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        $message_type = 'danger';
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($db);
    }
}

function category_new()
{
    require 'view/category_add.php';
}

function category_add($name, $cname, $nonce)
{
    $name = htmlspecialchars(strip_tags($name));
    $cname = htmlspecialchars(strip_tags($cname));
    try {
        if (csrf_verifyNonce('admin_category_add', $nonce) == true) {
            $db = DB();
            $sql = $db->prepare('INSERT INTO categories (name, cname) VALUES (?, ?)');
            $sql->bind_param('ss', $name, $cname);
            if ($sql->execute() === TRUE) {
                $message_type = 'success';
                $message = "New record " . $name . "(" . $cname . ") created successfully.";
            } else {
                $message_type = 'danger';
                $message = "ERROR: " . $sql . "<br>" . $db->error;
            }
        }else{
            $message_type = 'warning';
            $message = "Invalid operation.";
        }
    } catch (Exception $e) {
        $message_type = 'warning';
        $message = "Bad session.";
    }
}

function category_edit($cad_id)
{
    $db = DB();
    $sql = $db->prepare('SELECT * FROM categories WHERE catid=? LIMIT 1');
    $sql->bind_param('i', $cad_id);
    if ($sql->execute() && $result = $sql->get_result()) {
        if ($row = $result->fetch_assoc()) {
            $catid = $row['catid'];
            $name = $row['name'];
            $cname = $row['cname'];
            require 'view/category_edit.php';
            $result->free_result();
        }
    }
}

function category_save($catid, $name, $cname, $nonce)
{
    $name = htmlspecialchars(strip_tags($name));
    $cname = htmlspecialchars(strip_tags($cname));
    try {
        if (csrf_verifyNonce('admin_category_save', $nonce) == true) {
            $db = DB();
            $sql = $db->prepare("UPDATE categories SET name=?, cname=? WHERE catid=?");
            $sql->bind_param('ssi', $name, $cname, $catid);
            if ($sql->execute() === TRUE) {
                $message_type = 'success';
                $message = "Record updated successfully";
            } else {
                $message_type = 'danger';
                $message = "Error updating record: " . $db->error;
            }
        }else{
            $message_type = 'warning';
            $message = "Invalid operation.";
        }
    } catch (Exception $e) {
        $message_type = 'warning';
        $message = "Bad session.";
    }
    require 'view/message.php';
}

function category_del($cat_id)
{
    $db = DB();
    $sql = $db->prepare("DELETE FROM categories WHERE catid=?");
    $sql->bind_param('i', $cat_id);
    $message = ($sql->execute() === TRUE) ? "Deleted!" : "Error: " . $sql . "<br>" . $db->error;
    require 'view/message.php';
}

require 'view/header.php';

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'add':
            if (isset($_POST["action"]) && isset($_POST["name"]) && isset($_POST["cname"]) && $_POST["name"] != '' && isset($_POST["nonce"])) {
                category_add($_POST["name"], $_POST["cname"], $_POST["nonce"]);
            } else {
                category_new();
            }
            exit;
        case 'edit':
            if (isset($_GET["catid"])) {
                category_edit($_GET["catid"]);
            }
            exit;
        case 'save':
            if (isset($_POST["action"]) && isset($_POST["catid"]) && isset($_POST["name"]) && isset($_POST["cname"]) && $_POST["name"] != '' && isset($_POST["nonce"])) {
                category_save($_POST["catid"], $_POST["name"], $_POST["cname"], $_POST["nonce"]);
            }
            exit;
        case 'del':
            if (isset($_GET["catid"])) {
                category_del($_GET["catid"]);
            }
            exit;
        default:
            require_full_page('view/error.php');
    }
} else {
    category_main();
}

require 'view/footer.php';