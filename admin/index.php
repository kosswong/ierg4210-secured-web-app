<?php
require '../inc/config.inc.php';

if (auth_admin() !== false) {

    require 'view/nav.php';

    echo "<br />Welcome, ".auth_admin(). ". It is the admin panel.";

} else {
    header("Location: http://localhost");
    die();
}
?>