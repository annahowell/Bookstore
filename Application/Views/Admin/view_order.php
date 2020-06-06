<h1>Order details</h1>

<?php if ($body['order']['found']) { ?>

    <ul class="customerdetails">
        <li>Order no: <?php echo $body['orderNo'] ?></li>
        <li>Placed: <?php echo date('D d/m/Y - g:i A', strtotime($body['order']['user']['dateOrdered'])) ?></li>
        <br>
        <li><?php echo $body['order']['user']['firstName'] ?> <?php echo $body['order']['user']['lastName'] ?></li>
        <li><a href="mailto:<?php echo $body['order']['user']['email'] ?>"><?php echo $body['order']['user']['email'] ?></a></li>
        <br>
        <li><?php echo $body['order']['user']['add1'] ?></li>
        <li><?php echo $body['order']['user']['city'] ?></li>
        <li><?php echo $body['order']['user']['county'] ?></li>
        <li><?php echo $body['order']['user']['postcode'] ?></li>
    </ul>

    <table id="list-table">
        <tr>
            <th>Item</th>
            <th class="medium number">Quantity ordered</th>
            <th class="medium number">Unit price paid</th>
            <th class="small number">Price paid</th>
        </tr>

        <?php foreach ($body['order']['products'] as $prod) {
            if (is_array($prod)) {?>
                <tr>
                    <td><?php echo $prod['name'] ?></td>
                    <td class="medium number"><?php echo $prod['quantityOrdered'] ?></td>
                    <td class="medium number">£<?php echo number_format($prod['pricePaid'], 2) ?></td>
                    <td class="small number">£<?php echo number_format($prod['pricePaid'] * $prod['quantityOrdered'], 2) ?></td>
                </tr>
            <?php }
        } ?>
    </table>

    <p class="total">Total price paid:&nbsp;&nbsp;&nbsp;£<?php echo number_format($body['totalPaid'], 2) ?></p>

<?php } else { ?>
    <p>Order not found.</p>
<?php } ?>
