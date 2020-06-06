<h1>Add Product</h1>

<?php if ($body['categories']['found']) { ?>

    <form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/admin/add_product" enctype="multipart/form-data">
<fieldset>
    <legend>&nbsp;Add New Product&nbsp;</legend>

    <label for="name">Name:</label>
    <input required id="name" name="name" type="text">

    <label for="name">Author (optional):</label>
    <input id="author" name="author" type="text">

    <label for="isbn">ISBN (optional):</label>
    <input id="isbn" name="isbn" type="text">

    <label for="description">Description:</label>
    <textarea required id="description" name="description"></textarea>

    <label for="price">Price in sterling:</label>
    <input required id="price" name="price" type="number" step=".01">

    <label for="stockLevel">Current stock level:</label>
    <input required id="stockLevel" name="stockLevel" type="number" min="0">

    <label fpr="imageName">Select image (optional):</label>
    <input id="imageName" name="imageName" type="file">

    <label for ="categoryNo">Select category:</label>
    <select required id="categoryNo" name="categoryNo">
        <?php foreach ($body['categories'] as &$category) {
            if (is_array($category)) { ?>
                <option value="<?php echo $category['categoryNo'] ?>"><?php echo $category['name'] ?></option>
            <?php }
        }?>
    </select>

    <div id="button-container">
        <input type="submit" name="submit" value="Add Product">
        <a href="<?php echo URL_SUB_DIR ?>/admin/list_products" class="button">Cancel</a>
    </div>

    <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
</fieldset>
</form>

<?php } else { ?>
<p>You must add a category before adding a product.</p>
<div id="button-container">
    <a href="<?php echo URL_SUB_DIR ?>/admin/add_category" class="button">Add category</a>
</div>
<?php } ?>
