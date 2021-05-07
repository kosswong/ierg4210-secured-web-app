<?php
defined('IERG4210ADMIN') or define('IERG4210ADMIN', true);
require '../inc/config.inc.php';
if (auth_admin() == false) {
    header("Location: http://localhost");
    die();
}

$setting = 'main';
require 'view/header.php';
require 'view/main.php';
require 'view/footer.php';