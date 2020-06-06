<h1>Orders Placed</h1>

<?php if (isset($body['orders']['pagCount'])) { ?>
<p style="margin-top:0" class="tar">Per page value intentionally low to demonstrate pagination</p>
<?php } ?>

<?php if ($body['orders']['found']) { ?>

    <table id="list-table">
        <tr>
            <th>Customer</th>
            <th class="small">Order No</th>
            <th class="number">Date ordered</th>
            <th class="small number">Total paid</th>
            <th class="small">&nbsp;</th>
        </tr>

        <?php foreach ($body['orders'] as $order) {
            if (is_array($order)) {?>
                <tr>
                    <td><?php echo $order['firstName'] ?> <?php echo $order['lastName'] ?> (<?php echo $order['postcode'] ?>)</td>
                    <td class="small"><?php echo $order['orderNo'] ?></td>
                    <td class="number"><?php echo date('D d/m/Y - g:i A', strtotime($order['dateOrdered'])) ?></td>
                    <td class="small number">£<?php echo number_format($order['totalPaid'], 2) ?></td>
                    <td class="small"><a href="<?php echo URL_SUB_DIR ?>/admin/view_order/<?php echo $order['orderNo'] ?>">View</a></td>
                </tr>
            <?php }
        } ?>
    </table>

    <?php if (isset($body['orders']['pagCount'])) { ?>
    <div id="pagination">
        <?php for ($i = 1; $i <= $body['orders']['pagCount']; $i++) { ?>
            <a <?php if ($i == $body['pagNo']) { ?>class="selected"<?php } ?> href="<?php echo URL_SUB_DIR ?>/admin/list_orders/<?php echo $i ?>"><?php echo $i ?></a>
        <?php } ?>
    </div>
    <?php } ?>

<?php } else { ?>
    <p>No orders found<?php if ($body['searchTerm'] != '')?> while searching for <?php echo $body['searchTerm'] ?>.</p>
<?php } ?>
