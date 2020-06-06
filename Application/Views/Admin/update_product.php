<h1>Update Product</h1>

<?php if ($body['product']['found']) { ?>

<form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/admin/update_product/<?php echo $body['product']['productNo'] ?>" enctype="multipart/form-data">
<fieldset>
    <legend>&nbsp;Update Existing Product&nbsp;</legend>

    <label for="name">Name:</label>
    <input required id="name" name="name" type="text" value="<?php echo $body['product']['name'] ?>">

    <label for="name">Author (optional):</label>
    <input id="author" name="author" type="text" value="<?php echo $body['product']['author'] ?>">

    <label for="isbn">ISBN (optional):</label>
    <input id="isbn" name="isbn" type="text" value="<?php echo $body['product']['isbn'] ?>">

    <label for="description">Description:</label>
    <textarea required id="description" name="description"><?php echo $body['product']['description'] ?></textarea>

    <label for="price">Price in sterling:</label>
    <input required id="price" name="price" type="number" step=".01" value="<?php echo number_format($body['product']['price'], 2) ?>">

    <label for="stockLevel">Current stock level:</label>
    <input required id="stockLevel" name="stockLevel" type="number" min="0" value="<?php echo $body['product']['stockLevel'] ?>">

    <label>Existing image: </label>
    <img class="product-update-preview" src="<?php echo URL_SUB_DIR ?>/images/<?php echo $body['product']['imageName'] ?>">

    <label fpr="imageName">Select new image (optional):</label>
    <input id="imageName" name="imageName" type="file" accept=".jpg, .jpeg, .png">

    <label for="categoryNo">Select category:</label>
    <select required id="categoryNo" name="categoryNo">
        <?php foreach ($body['categories'] as &$category) {
            if (is_array($category)) { ?>
                <option <?php if ($body['product']['categoryNo'] == $category['categoryNo']) { ?>selected<?php } ?> value="<?php echo $category['categoryNo'] ?>"><?php echo $category['name'] ?></option>
            <?php }
        }?>
    </select>

    <label for="removed">Remove product from sale:</label>
    <select required name="removed">
        <option <?php if ($body['product']['removed'] == 0) { ?>selected<?php } ?> value="0">No</option>
        <option <?php if ($body['product']['removed'] == 1) { ?>selected<?php } ?> value="1">Yes</option>
    </select>

    <div id="button-container">
        <input type="submit" name="submit" value="Update Product">
        <a href="<?php echo URL_SUB_DIR ?>/admin/list_products" class="button">Cancel</a>
    </div>

    <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
</fieldset>
</form>

<?php } else { ?>
<p>Product not found.</p>
<?php } ?>
