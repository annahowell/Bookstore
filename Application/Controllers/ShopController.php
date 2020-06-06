<?php
require_once APP_DIR . '/Core/Controller.php';

/**
 * This is the shop controller which handles the server side logic for the viewing of products and categories,
 * cart handling and search and checkout logic.
 *
 * Date: 12th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class ShopController extends Controller
{
    function __construct($model)
    {
        parent::__construct($model);
        // We adopt a default permission of false to each controller as best security practice, therefore we
        // need to set our permission variable accordingly, in this case permission is unconditional as anyone
        // can access this.
        $this->permission = TRUE;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Simply forwards anyone landing on the index page to the list_products page
     */
    public function index()
    {
        header('Location: ' . URL_SUB_DIR . '/shop/list_products');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for exposing a specific product's data to the view
     *
     * @param $params       Int of the product to displays. Passed to the controller by Application.php
     */
    public function view_product($params)
    {
        $this->body['location'] = 'shop';
        $this->body['product'] = $this->model->getRowFromTable('product', 'productNo', $params[0]);

        if (isset($params[1]) && $params[1] == 1)
        {
            $this->body['error'] = 'Unable to add to cart, the requested quantity exceeded our stock';
        }

        $this->render('Shop', 'view_product');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns a list of products based on many criteria. In it's default state it will return all non-removed
     * products arranged in ascending order by name.
     *
     * This function returns a json object so that we can fulfil a component of the AJAX marking criteria
     */
    public function list_products()
    {
        $this->body['location'] = 'shop';

        if (isset($_POST['submit']) && isset($_POST['ajax']))
        {
            // Setup params from the url array with some sane defaults if values are absent via unary operator
            $searchTerm  = strtolower($_POST['searchTerm']);
            $categoryNo  = isset($_POST['categoryNo']) ? $_POST['categoryNo'] : 'all';
            $pagNo       = isset($_POST['pagNo'])      ? $_POST['pagNo'] : '1';
            $orderBy     = isset($_POST['orderBy'])    ? $_POST['orderBy'] : 'name';
            $orderDir    = isset($_POST['orderDir'])   ? $_POST['orderDir'] : 'ASC';

            // We need to expose our chosen vars back to the view for pagination
            $this->body['$catFilter'] = $categoryNo;
            $this->body['pagNo']      = $pagNo;
            $this->body['$orderBy']   = $orderBy;
            $this->body['$orderDir']  = $orderDir;

            // We accept any combination of uppercase or lower case 'all' as a valid value for category to
            // make life easier for humans so redefine that to a value SQL likes instead
            if (strtolower($categoryNo) == 'all')
            {
                $categoryNo = '%';
            }

            if ($searchTerm == '' || $searchTerm == '*' || $searchTerm == 'all' || $searchTerm == 'everything')
            {
                $searchTerm = '%';
            }

            $numPerPage = 6;
            $offset = $numPerPage * ($pagNo - 1); // 6 products per page

            $this->body['products'] = $this->model->getLimitedOrderedRows($categoryNo, '%' .  $searchTerm . '%', $orderBy, $orderDir, $numPerPage, $offset); // 6 products per page

            echo json_encode($this->body);
        }
        else
        {
            $this->render('Shop', 'list_products');
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for adding an item to the cart, validation is handled by the model
     *
     * @param $productNo       Int of the product to add to cart. Passed to the controller by Application.php
     */
    public function add_to_cart($params)
    {
        if(isset($_POST['submit']))
        {
            $result = $this->model->addToCart($_POST, $params[0]);

            if ($result['success'])
            {
                $productExistsInCart = FALSE;

                // PDO will have already done the $productNo[0] check  with bindparam on the $result = line
                // for us to reach this TRUE condition block so we're safe to use $productNo[0] as is.
                foreach ($_SESSION['cart'] as &$item)
                {
                    // If the product we're adding to cart is already in the cart
                    if ($item['productNo'] == $params[0])
                    {
                        // Increment the quantity of it in the cart
                        $item['quantity'] += $_POST['quantity'];
                        $productExistsInCart = TRUE;
                    }
                }

                // Otherwise add it to cart as a new product
                if (!$productExistsInCart)
                {
                    $newItem['productNo'] = $params[0];
                    $newItem['name'] = $result['name'];
                    $newItem['price'] = $result['price'];
                    $newItem['quantity'] = $_POST['quantity'];

                    array_push($_SESSION['cart'], $newItem);
                }

                $this->updateCartTotal();

                header('Location: ' . URL_SUB_DIR . '/shop/view_cart');
            }
            else
            {
                header('Location: ' . URL_SUB_DIR . '/shop/view_product/' . $params[0] . '/1');
            }
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * No real error checking done here, if the productNo passed doesn't exist in the session's cart array
     * (because the end user has manually edited the parameter passed in the url) nothing is found in the
     * below check and we just refresh the page; the end user has simply wasted their own time. We'll sanitise
     * the var passed by casting to int.
     *
     * @param $productNo       Int of the product to remove from cart. Passed to controller by Application.php
     */
    public function remove_from_cart($params)
    {
        foreach ($_SESSION["cart"] as $key => $value)
        {
            if ($value["productNo"] == (int)$params[0])
            {
                unset($_SESSION["cart"][$key]);
            }
        }

        $this->updateCartTotal();

        header('Location: ' . URL_SUB_DIR . '/shop/view_cart');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for viewing the cart.
     */
    public function view_cart()
    {
        $this->updateCartTotal();

        $this->body['location'] = 'cart';
        $this->body['cartTotal'] = $_SESSION['cartTotal'];
        // It's bad practice to expose the SESSION data directly in the view
        $this->body['cart'] = $_SESSION['cart'];

        $this->render('Shop', 'view_cart');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for displaying the placeholder payment_method page..
     */
    public function payment_method()
    {
        $this->body['location'] = 'cart';
        $this->body['cartTotal'] = $_SESSION['cartTotal'];

        $this->render('Shop', 'payment_method');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for placing an error from products in SESSION. The model handles validation and DB logic
     */
    public function place_order()
    {
        $this->body['location'] = 'cart';

        // Just in case a guest or admin user attempts to checkout via editing the url test their session
        if (isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 1)
        {
            $result = $this->model->placeOrder($_SESSION['cart'], $_SESSION['cartTotal'], $_SESSION['userNo']);

            if ($result['success'])
            {
                $this->updateCartTotal();

                $this->body['message'] = 'Your oder was successful. thank you for your custom';
            }
            else
            {
                $this->body['message'] = $result['error'];
            }

        }
        else
        {
            $this->body['message'] = 'Sorry you must be logged in as a standard user to place an order';
        }

        $this->render('Shop', 'order_confirmation');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Private function to update cart total, called by functions that change the items in the cart
     */
    private function updateCartTotal()
    {
        // Reading and writing directly to the session file inside a loop is bad practice so we'll use a temp
        // variable
        $cartTotal = 0;

        foreach ($_SESSION["cart"] as $key => $value)
        {
            $cartTotal += $value['price'] * $value['quantity'];
        }

        $_SESSION["cartTotal"] = $cartTotal;
    }
}
