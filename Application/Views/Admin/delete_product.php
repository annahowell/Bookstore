<h1>Delete product</h1>

<?php if ($body['content']['found']) { ?>

<form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/admin/delete_product/<?php echo $body['content']['productNo'] ?>">
<fieldset>
    <legend>&nbsp;Delete Product&nbsp;</legend>
    <p style="">Are you sure you wish to delete product '<?php echo $body['content']['name'] ?>' ?</p>

    <div id="button-container">
        <input type="submit" name="submit" value="Delete Product">
        <a href="<?php echo URL_SUB_DIR ?>/admin/list_products" class="button">Cancel</a>
    </div>
    <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
</fieldset>
</form>

<?php } else { ?>
    <p>Unable to delete nonexistent product.</p>
<?php } ?>
