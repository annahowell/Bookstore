<h1>Category List</h1>

<div id="button-container">
    <a href="<?php echo URL_SUB_DIR ?>/admin/add_category" class="button">Add category</a>
</div>

<?php if ($body['categories']['found']) { ?>

<table id="list-table">
    <tr>
        <th><span onclick='sortTable("name");'>Name</th>
        <th class="small">&nbsp;</th>
        <th class="small">&nbsp;</th>
    </tr>

    <?php foreach ($body['categories'] as &$category) {
        if (is_array($category)) {?>
        <tr>
            <td class="name"><?php echo $category['name'] ?></td>
            <td class="small"><a href="<?php echo URL_SUB_DIR ?>/admin/update_category/<?php echo $category['categoryNo'] ?>">update</a></td>
            <td class="small"><a href="<?php echo URL_SUB_DIR ?>/admin/delete_category/<?php echo $category['categoryNo'] ?>">delete</a></td>
        </tr>
    <?php }
    } ?>
</table>

<?php } else { ?>
    <p>No categories found.</p>
<?php } ?>
