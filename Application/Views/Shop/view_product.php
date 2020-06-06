<?php if ($body['product']['found']) { ?>

    <section class="full">
        <div class="cb">
            <h1><?php echo $body['product']['name']; ?></h1>

            <img src="<?php echo URL_SUB_DIR ?>/images/<?php echo $body['product']['imageName'] ?>">

            <div class="info cb">
                <p><?php echo $body['product']['author']; ?></p>
                <p>£<?php echo number_format($body['product']['price'], 2) ?></p>
                <p><?php if ($body['product']['isbn'] != '') { echo $body['product']['isbn']; }?></p>
                <p style="<?php if ($body['product']['stockLevel'] > 1) {?>color:#0D0<?php } else { ?>color:#D00<?php } ?>"><?php echo $body['product']['stockLevel']?> in stock</p>

                <?php if ($body['product']['stockLevel'] > 0) { ?>
                <form method="post" id="addtobasketform" action="<?php echo URL_SUB_DIR ?>/shop/add_to_cart/<?php echo $body['product']['productNo']; ?>">
                    <label for="quantity">Quantity:</label>
                    <input required id="quantity" name="quantity" type="number" min="1" 6max="<?php echo $body['product']['stockLevel']?>" value="1">

                    <div id="button-container">
                        <input id="submit" type="submit" name="submit" value="Add to cart">
                    </div>
                </form>
                <?php } ?>

                <?php if (isset($body['userLevel']) && $body['userLevel'] == 0) { ?>
                <div style="padding-top:20px" id="button-container">
                    <a href="/bookstore/admin/update_product/<?php echo $body['product']['productNo'] ?>" class="button">Update product</a>
                </div>
                <?php } ?>

                <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>

                <p class="description"><?php echo nl2br($body['product']['description']) ?></p>
            </div>
        </div>
    </section>

<?php } else { ?>
    <p>Category not found.</p>
<?php } ?>