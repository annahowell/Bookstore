<h1>Delete Category</h1>

<?php if ($body['content']['found']) { ?>

<form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/admin/delete_category/<?php echo $body['content']['categoryNo'] ?>">
<fieldset>
    <legend>&nbsp;Delete Category&nbsp;</legend>
    <p style="">Are you sure you wish to delete the '<?php echo $body['content']['name'] ?>' category?</p>

    <div id="button-container">
        <input type="submit" name="submit" value="Delete Category">
        <a href="<?php echo URL_SUB_DIR ?>/admin/list_categories" class="button">Cancel</a>
    </div>

    <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
</fieldset>
</form>

<?php } else { ?>
    <p>Unable to delete nonexistent category.</p>
<?php } ?>
