<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link href="../css/fontawesome.css" rel="stylesheet" type="text/css">
    <link href="../css/admin.css" rel="stylesheet">
    <link href="../css/font.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">
    <?php require 'nav.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <?php echo filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL); ?>
                        </span>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">