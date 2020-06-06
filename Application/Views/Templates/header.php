<!DOCTYPE html>
<html lang="en" DIR="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bookstore - Anna Thomas(s4927945)</title>

    <link rel="shortcut icon" href="<?php echo URL_SUB_DIR ?>/images/favicon.ico">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link type="text/css" rel="stylesheet" href="<?php echo URL_SUB_DIR ?>/css/style.css"/>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
</head>

<body>

<header>
    <div class="container cb">
        <div id="logo">
            <h1><a href="<?php echo URL_SUB_DIR ?>/">Bookstore</a></h1>
        </div>

        <p class="welcome cb">Welcome <?php if (isset($body['username'])) { echo $body['username']; }?></p>

        <nav>
            <ul>
                <li <?php if (isset($body['location']) && $body['location'] == 'shop') { ?>class="active"<?php } ?>><a href="<?php echo URL_SUB_DIR ?>/shop/list_products">Shop</a></li>
                <?php if (isset($body['username']) && $body['userLevel'] == 0) { ?>
                    <li <?php if (isset($body['location']) && $body['location'] == 'products') { ?>class="active"<?php } ?>><a href="#">Products&nbsp;&nbsp;v</a>
                        <ul>
                            <li><a href="<?php echo URL_SUB_DIR ?>/admin/list_products">List / Update / Delete</a></li>
                            <li><a href="<?php echo URL_SUB_DIR ?>/admin/add_product">Add New</a></li>
                        </ul>
                    </li>
                    <li <?php if (isset($body['location']) && $body['location'] == 'categories') { ?>class="active"<?php } ?>><a href="#">Categories&nbsp;&nbsp;v</a>
                        <ul>
                            <li><a href="<?php echo URL_SUB_DIR ?>/admin/list_categories">List / Update / Delete </a></li>
                            <li><a href="<?php echo URL_SUB_DIR ?>/admin/add_category">Add New</a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo URL_SUB_DIR ?>/admin/list_orders">Orders</a></li>
                <?php } ?>
                <li <?php if (isset($body['location']) && $body['location'] == 'cart') { ?>class="active"<?php } ?>><a href="<?php echo URL_SUB_DIR ?>/shop/view_cart">Cart (<?php if (isset($body['cart'])) { echo count($body['cart']); } ?>)</a></li>

                <?php if (isset($body['location']) && isset($body['username']) && $body['userLevel'] != 2) { ?>
                    <li><a href="<?php echo URL_SUB_DIR ?>/user/logout">Logout</a></li>


                <?php
                    } else if (isset($body['location'])) { ?>
                    <li <?php if (isset($body['location']) && $body['location'] == 'login') { ?>class="active"<?php } ?>><a href="<?php echo URL_SUB_DIR ?>/user/login">Login</a></li>
                    <li <?php if (isset($body['location']) && $body['location'] == 'register') { ?>class="active"<?php } ?>><a href="<?php echo URL_SUB_DIR ?>/user/register">Register</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <div class="container search">
        <?php if (isset($body['username']) && $body['userLevel'] == 0) { ?>
            <form method="post" class="searchform" action="<?php echo URL_SUB_DIR ?>/admin/search_orders">
                <input required class="search" name="search" type="text" value="">

                <input type="submit" name="submit" value="Search Orders">
            </form>
        <?php } ?>
    </div>

</header>

<main>
    <div class="container">
