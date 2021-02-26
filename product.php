<?php require 'header.php'; ?>

<main class="container">
    <nav aria-label="breadcrumb" id="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item"><a href="index.html">Category 1</a></li>
            <li aria-current="page" class="breadcrumb-item active">ORGANIC SENCHA TEA BAG</li>
        </ol>
    </nav>
</main>


<section class="position-relative bg-light" id="categories">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <img alt="..." class="card-img-top" src="img/product/8994473017374.jpg">
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">ORGANIC SENCHA TEA BAG</h5>
                        <p class="card-text"><s>HK$45.90</s> $38.00</p>
                        <p>Mauris viverra cursus ante laoreet eleifend. Donec vel fringilla ante. Aenean finibus velit
                            id urna vehicula, nec maximus est sollicitudin.</p>
                        <form class="form-inline">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="amount">Amount</label>
                                <select class="form-control" id="amount">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning mb-2">Add to cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'footer.php'; ?>