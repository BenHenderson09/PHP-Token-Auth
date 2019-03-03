<div class="container">
    <?php if (!empty($_SESSION['msgs'])): ?>
        <?php foreach($_SESSION['msgs'] as $msg):?>
            <br/>
            <div class="alert alert-<?= $msg['success'] ? 'success' : 'danger' ?>" role="alert">
                <?= $msg['message']?>
            </div>
        <?php endforeach;?>
        <?php unset($_SESSION['msgs'])?>
    <?php endif;?>
</div>