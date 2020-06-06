<h1>Shopping cart</h1>

<?php if (count($body['cart']) > 0) { ?>

    <table id="list-table">
        <tr>
            <th>Item</th>
            <th class="small number">Quantity</th>
            <th class="small number">Unit price</th>
            <th class="small number">Price</th>
            <th class="small">&nbsp;</th>
        </tr>

        <?php foreach ($body['cart'] as &$prod) {
            if (is_array($prod)) {?>
                <tr>
                    <td><a href="<?php echo URL_SUB_DIR ?>/shop/view_product/<?php echo $prod['productNo'] ?>"><?php echo $prod['name'] ?></a></td>
                    <td class="small number"><?php echo $prod['quantity'] ?></td>
                    <td class="small number"><?php echo number_format($prod['price'], 2) ?></td>
                    <td class="small number"><?php echo number_format($prod['quantity'] * $prod['price'], 2) ?></td>
                    <td class="small"><a href="<?php echo URL_SUB_DIR ?>/shop/remove_from_cart/<?php echo $prod['productNo'] ?>">remove</a></td>
                </tr>
            <?php }
        } ?>
    </table>

    <p class="total">Total price:&nbsp;&nbsp;&nbsp;£<?php echo number_format($body['cartTotal'], 2) ?></p>

    <div id="button-container" style="text-align:right">
        <a href="<?php echo URL_SUB_DIR ?>/" class="button">Continue Shopping</a>
        <?php if (isset($body['username']) && $body['userLevel'] == 0) { ?>
            <p style="padding-top:20px">You must login as a non-admin to make purchases.</p>
        <?php } else if (isset($body['username']) && $body['userLevel'] == 1) { ?>
            <a href="<?php echo URL_SUB_DIR ?>/shop/payment_method" class="button">Checkout</a>
        <?php } else { ?>
            <a href="<?php echo URL_SUB_DIR ?>/user/login" class="button">Login</a>
            <a href="<?php echo URL_SUB_DIR ?>/user/register" class="button">Register and checkout</a>
        <?php } ?>
    </div>

<?php } else { ?>
    <p>Your shopping cart is empty.</p>

    <div id="button-container">
        <a href="<?php echo URL_SUB_DIR ?>/" class="button">Shop now!</a>
    </div>
<?php } ?>
