<h1>Product List</h1>

<div id="button-container">
    <a href="<?php echo URL_SUB_DIR ?>/admin/add_product" class="button">Add product</a>
</div>

<?php if ($body['products']['found']) { ?>

    <table id="list-table">
    <tr>
        <th>Name</th>
        <th class="medium">Removed from sale</th>
        <th class="small number">Price</th>
        <th class="small number">Stock</th>
        <th class="small">&nbsp;</th>
        <th class="small">&nbsp;</th>
    </tr>

    <?php foreach ($body['products'] as $prod) {
        if (is_array($prod)) {?>
        <tr>
            <td><?php echo $prod['name'] ?></td>
            <td class="medium"><?php if ($prod['removed'] == 1) { ?>Yes<?php } else {?>No<?php } ?></td>
            <td class="small number">£<?php echo number_format($prod['price'], 2) ?></td>
            <td class="small number"><?php echo $prod['stockLevel'] ?></td>
            <td class="small"><a href="<?php echo URL_SUB_DIR ?>/admin/update_product/<?php echo $prod['productNo'] ?>">update</a></td>
            <td class="small"><a href="<?php echo URL_SUB_DIR ?>/admin/delete_product/<?php echo $prod['productNo'] ?>">delete</a></td>
        </tr>
    <?php }
    } ?>
    </table>


    <div id="pagination">
        <?php for ($i = 1; $i <= $body['products']['pagCount']; $i++) { ?>
            <a <?php if ($i == $body['pagNo']) { ?>class="selected"<?php } ?> href="<?php echo URL_SUB_DIR ?>/admin/list_products/<?php echo $i ?>"><?php echo $i ?></a>
        <?php } ?>
    </div>

<?php } else { ?>
    <p>No products found.</p>
<?php } ?>
