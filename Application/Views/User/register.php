<form method="post" id="inlined" action="<?php echo URL_SUB_DIR ?>/user/register" enctype="multipart/form-data">
    <fieldset>
        <legend>&nbsp;Register Account&nbsp;</legend>

        <label for="username">User name<span> (min 4 chars)</span></label>
        <input required id="username" pattern=".{4,}" name="username" type="text">
        <br>
        <label for="password">Password<span> (min 6 chars)</span></label>
        <input required id="password" pattern=".{6,}" name="password" type="password">
        <br>
        <label for="passwordConfirmation">Confirm password</label>
        <input required id="passwordConfirmation" pattern=".{6,}" name="passwordConfirmation" type="password">
        <br>
        <label for="firstName">First name</label>
        <input required id="firstName" name="firstName" type="text">
        <br>
        <label for="lastName">Last name</label>
        <input required id="lastName" name="lastName" type="text">
        <br>
        <label for="email">Email</label>
        <input required id="email" name="email" type="email">
        <br>
        <label for="address">Address</label>
        <input required id="address" name="address" type="text">
        <br>
        <label for="city">City</label>
        <input required id="city" name="city" type="text">
        <br>
        <label for="county">County</label>
        <input required id="county" name="county" type="text">
        <br>
        <label for="postcode">Postcode</label>
        <input required id="postcode" name="postcode" type="text">
        <br>
        <div id="button-container">
            <input type="submit" name="submit" value="Register">
            <a href="<?php echo URL_SUB_DIR ?>/" class="button">Cancel</a>
        </div>

        <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>

    </fieldset>
</form>

