<?php
if (!defined('IERG4210')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>

<footer class="container">
    <p>&copy; Company 2021 <?=(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) ? " | <a href='../admin/categories.php'>Admin</a></p>" : ''?>
</footer>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/cart.js"></script>
</body>
</html>