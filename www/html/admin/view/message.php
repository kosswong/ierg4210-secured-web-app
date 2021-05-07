<?php
if (!defined('IERG4210ADMIN')){
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<?php if(isset($message)){ ?>
    <div class="card bg-<?=isset($message_type) ? $message_type : 'success'?> text-white shadow">
        <div class="card-body">
            <?=$message?>
        </div>
    </div>
<?php } ?>