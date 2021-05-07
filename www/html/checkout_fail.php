<?php
require 'inc/config.inc.php';
unset($_SESSION['4210SHOP']);
require_header();
?>
Fail!
<script>localStorage.removeItem('shopping_cart');</script>
<?php
require_footer();
?>