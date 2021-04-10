<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>

<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active"><?php echo $current_catname ?></li>
        </ol>
    </nav>
    <div class="featurette">
        <h2 class="featurette-heading">Shop Heath.
            <span class="text-muted">It'll blow your mind.</span>
        </h2>
        <p class="lead"> “Health is a state of complete harmony of the body, mind and spirit. When one is free from
            physical disabilities and mental distractions, the gates of the soul open.” – B.K.S. Iyengar</p>
    </div>
</main>


<section class="position-relative bg-light" id="categories">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="list-group" id="list-tab" role="tablist">
                    <?= get_cat_list() ?>
                </div>
            </div>
            <div class="col-9">
                <div class="row">
                    <?= $p_list ?>
                </div>
            </div>
        </div>
    </div>
</section>
