<form method="post" id="inlined" action="<?php echo URL_SUB_DIR ?>/user/login">
<fieldset>
    <legend>&nbsp;User Login&nbsp;</legend>

    <label for="username">Username:</label>
    <input required id="username" name="username" type="text" placeholder="Username">
    <br>
    <label for="password">Password:</label>
    <input required id="password" name="password" type="password" placeholder="Password">

    <div id="button-container">
        <input type="submit" name="submit" value="Login">
        <a href="<?php echo URL_SUB_DIR ?>/user/register" class="button">Register</a>
    </div>

    <span id="error"><?php if (isset($body['message'])) { echo($body['message']); } ?></span>
</fieldset>
</form>