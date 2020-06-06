<h1>Update category</h1>

<form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/admin/update_category/<?php echo $body['content']['categoryNo'] ?>">
<fieldset>
    <legend>&nbsp;Update Existing Category&nbsp;</legend>

    <label for="name">Name:</label>
    <input required id="name" name="name" type="text" value="<?php echo $body['content']['name'] ?>">

    <div id="button-container">
        <input type="submit" name="submit" value="Update Category">
        <a href="<?php echo URL_SUB_DIR ?>/admin/list_categories" class="button">Cancel</a>
    </div>

    <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
</fieldset>
</form>