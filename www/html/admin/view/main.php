<?php
if (!defined('IERG4210ADMIN')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Welcome</h6>
    </div>
    <div class="card-body">
        <p>Welcome, <?php echo filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL); ?>. It is the admin panel.</p>
    </div>
</div>