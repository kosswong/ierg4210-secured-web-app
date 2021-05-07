<?php
header("HTTP/1.0 404 Not Found");

require 'inc/config.inc.php';
unset($_SESSION['4210SHOP']);

$get_pid = (isset($_GET['pid']) && is_numeric($_GET['pid']) && ($_GET['pid'] > 0)) ? intval($_GET['pid']) : 1;

$db = DB();
$sql = $db->prepare('SELECT products.*, categories.name as cname FROM products JOIN categories ON products.catid = categories.catid WHERE pid=? LIMIT 1');
$sql->bind_param('i', $get_pid);
$sql->execute();

if ($sql->execute() && $result = $sql->get_result()) {
    if ($row = $result->fetch_assoc()) {
        $pid = intval($row['pid']);
        $catid = intval($row['catid']);
        $name = htmlspecialchars(strip_tags($row['name']));
        $price = floatval($row['price']);
        $description = htmlspecialchars(strip_tags($row['description']));
        $image = urlencode($row['image']);
        $catname = htmlspecialchars(strip_tags($row['cname']));
    }else{
        exit;
    }
}

require_header();
?>

<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li aria-current="page" class="breadcrumb-item active">Not found</li>
        </ol>
    </nav>
</main>

<section class="container bg-light" id="categories">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <div class="error mx-auto" data-text="404">404</div>
                    <p class="lead text-gray-800 mb-5">Page Not Found</p>
                    <p class="text-gray-500 mb-0">The page you visiting is not find. Be an ethical hacker ok?</p>
                    <a href="index.php">← Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_footer();?>