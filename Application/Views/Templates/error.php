<h1><?php echo $body['errorCode'] ?></h1>

<?php if (DEV) { ?>
    <pre style="white-space:pre-wrap;"><?php var_dump($body['error']) ?></pre>
<?php } else { ?>

<p><?php echo $body['error'] ?></p>
<?php } ?>
