<h1>Select payment method</h1>

<p style="text-align:center;max-width:90%;margin:40px auto">At this stage the user would be able to choose a third party
    payment method such as paypal, worldpay or similar. A link would be provided, taking the user to the appropriate
    payment website where they enter their payment details.</p>

<p style="text-align:center;max-width:90%;margin:40px auto">This hands off responsibility and instills greater
    confidence in the customer as they're entering their details in to a more well-known and therefore trusted page.</p>

<p style="text-align:center;max-width:90%;margin:40px auto">After successfully entering their payment details through
    the relevant payment portal the user is returned to the store webstite URL and makes a final order confirmation
    below.</p>


<div id="button-container" style="text-align:right">
        <a href="<?php echo URL_SUB_DIR ?>/shop/place_order" class="button">Place order totalling £<?php echo number_format($body['cartTotal'], 2) ?></a>
</div>