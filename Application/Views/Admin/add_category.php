<h1>Add Category</h1>

<p class="message"><?php if (isset($body['message'])) { echo($body['message']); } ?></p>

<form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/admin/add_category">
<fieldset>
    <legend>&nbsp;Add New Category&nbsp;</legend>

    <label for="name">Name:</label>
    <input required id="name" name="name" type="text">

    <div id="button-container">
        <input type="submit" name="submit" value="Add Category">
        <a href="<?php echo URL_SUB_DIR ?>/admin/list_categories" class="button">Cancel</a>
    </div>

    <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
</fieldset>
</form>