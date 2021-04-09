<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-text mx-3">ADMIN SYSTEM</div>
    </a>

    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Market</div>
    <li class="nav-item">
        <a class="nav-link" href="products.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Products</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="categories.php">
            <i class="fas fa-fw fa-table"></i>
            <span>Category</span>
        </a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">Client side</div>
    <li class="nav-item">
        <a class="nav-link" href="../index.php">
            <i class="fas fa-fw fa-table"></i>
            <span>Homepage</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../user.php?action=password">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Change Password</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../user.php?action=logout">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>
</ul>