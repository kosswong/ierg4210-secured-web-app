<?php
if (!defined('IERG4210')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>


</div>

<!-- Modal: add item successfully -->
<div class="modal fade" id="add-item-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Shopping cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="add-item-modal-content">

            </div>
            <div class="modal-footer" id="add-item-modal-button">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


<footer class="container">
    <p>Auth: Wong Keng Lam</p>
</footer>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/custom.js"></script>
</body>
</html>