<?php
require_once APP_DIR . '/Core/Controller.php';

/**
 * This is the admin controller which handles the server side logic for the adding, viewing, updating,
 * deleting and disabling of products, the adding, viewing, updating and deletion of categories and the
 * viewing of orders.
 *
 * Date: 11th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class AdminController extends Controller
{
    function __construct($model)
    {
        parent::__construct($model);

        // We adopt a default permission of false to each controller as best security practice, therefore we
        // need to set our permission variable accordingly, in this case permission is dependent on the user
        // being logged in as an admin.
        if (isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 0)
        {
            $this->permission = TRUE;
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Since the default controller method is index we need to handle the possibility the admin lands here
     */
    public function index()
    {
        header('Location: ' . URL_SUB_DIR . '/admin/list_orders');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Populates the $body var with a complete list of products ready for the view to display the data
     */
    public function list_products($params)
    {
        // Setup params for pagination with a sane default of page 1 using a unary operator
        $pagNo = isset($params[0]) ? $params[0] : '1';
        $numPerPage = 10;
        $offset = $numPerPage * ($pagNo - 1);

        $this->body['pagNo'] = $pagNo;
        $this->body['products'] = $this->model->getProducts($numPerPage, $offset);

        $this->render('Admin', 'list_products');
    }



    /** ------------------------------------------------------------------------------------------------------
     * This method simply sets the location var and renders the correct view, since $this->body['categories']
     * is populated in the controller super class
     */
    public function list_categories()
    {
        $this->body['location'] = 'categories';

        $this->render('Admin', 'list_categories');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Populates the $body var with a complete list of orders ready for the view to display the data
     */
    public function list_orders($params)
    {
        $this->body['location'] = 'orders';

        // Setup params for pagination with a sane default of page 1 using a unary operator
        $pagNo = isset($params[0]) ? $params[0] : '1';
        $numPerPage = 5;
        $offset = $numPerPage * ($pagNo - 1);

        $this->body['pagNo'] = $pagNo;
        $this->body['orders'] = $this->model->getOrders($numPerPage, $offset);

        $this->render('Admin', 'list_orders');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles basic search functionality for products
     */
    public function search_orders()
    {
        $this->body['location'] = 'orders';

        if (isset($_POST['submit']))
        {
            $this->body['searchTerm'] = strip_tags($_POST['search']);
            $this->body['orders'] = $this->model->searchOrder('%' . $_POST['search'] . '%');
        }
        else
        {
            $this->body['searchTerm'] = '';
        }

        $this->render('Admin', 'list_orders');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Populates the $body var with details about a given order no
     *
     * @param $orderNo       Int of the order to display the details. Passed to controller by Application.php
     */
    public function view_order($params)
    {
        $this->body['location'] = 'orders';
        // $body is sanitised through the universal sanitise function in Controller.php before being exposed
        // to the view so we're fine to loop-back the passed var
        $this->body['orderNo'] = $params[0];
        $order = $this->model->getOrderDetails($params[0]);

        if ($order['found'])
        {
            $this->body['totalPaid'] = 0;

            foreach ($order["products"] as $key => $value)
            {
                $this->body['totalPaid'] += $value['pricePaid'] * $value['quantityOrdered'];
            }
        }

        $this->body['order'] = $order;

        $this->render('Admin', 'view_order');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for adding a new product. Validation and adding to the DB is handed off to
     * the model
     */
    public function add_product()
    {
        $this->body['location'] = 'products';

        if(isset($_POST['submit']))
        {
            $result = $this->model->addProduct($_POST, $_FILES['imageName']);

            if (!$result['success'])
            {
                $this->body['error'] = $result['error'];
            }
            else
            {
                header('Location: ' . URL_SUB_DIR . '/admin/list_products');
            }
        }

        $this->render('Admin', 'add_product');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for adding a new category. Validation and adding to the DB is handed off to
     * the model
     */
    public function add_category()
    {
        $this->body['location'] = 'categories';

        if(isset($_POST['submit']))
        {
            $result = $this->model->addCategory($_POST);

            if (!$result['success'])
            {
                $this->body['error'] = $result['error'];
            }
            else
            {
                header('Location: ' . URL_SUB_DIR . '/admin/list_categories');
            }
        }

        $this->render('Admin', 'add_category');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for updating a product. Validation and adding to the DB is handed off to the
     * model
     *
     * @param $productNo       Int of the product to display details. Passed to controller by Application.php
     */
    public function update_product($params)
    {
        $this->body['location'] = 'products';
        $this->body['product'] = $this->model->getRowFromTable('product', 'productNo', $params[0]);

        if(isset($_POST['submit']))
        {
            $result = $this->model->updateProduct($_POST, $_FILES['imageName'], $params[0], $this->body['product']['imageName']);

            if (!$result['success'])
            {
                $this->body['error'] = $result['error'];
            }
            else
            {
                header('Location: ' . URL_SUB_DIR . '/admin/list_products');
            }
        }

        $this->render('Admin', 'update_product');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for updating a category. Validation and adding to the DB is handed off to the
     * model
     *
     * @param $categoryNo       Int of the category to display. Passed to controller by Application.php
     */
    public function update_category($params)
    {
        $this->body['location'] = 'categories';
        $this->body['content'] = $this->model->getRowFromTable('category', 'categoryNo', $params[0]);

        if(isset($_POST['submit']))
        {
            $result = $this->model->updateCategory($_POST, $params[0]);

            if (!$result['success'])
            {
                $this->body['error'] = $result['error'];
            }
            else
            {
                header('Location: ' . URL_SUB_DIR . '/admin/list_categories');
            }
        }

        $this->render('Admin', 'update_category');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for deleting a product. Validation and adding to the DB is handed off to the
     * model
     *
     * @param $productNo       Int of the product to display details. Passed to controller by Application.php
     */
    public function delete_product($params)
    {
        $this->body['location'] = 'products';
        $this->body['content'] = $this->model->getRowFromTable('product', 'productNo', $params[0]);

        if(isset($_POST['submit']))
        {
            try
            {
                if ($this->model->deleteProduct($params[0], $this->body['content']['imageName']))
                {
                    header('Location: ' . URL_SUB_DIR . '/admin/list_products');
                }
            }
            catch (\Exception $e)
            {
                if ($e->getCode() == 23000) // Integrity constraint
                {
                    $this->body['error'] = 'Deletion failed; product in use by a completed order. You can only remove this product from sale using the update product page!';
                }
                else
                {
                    throw $e;
                }
            }
        }

        $this->render('Admin', 'delete_product');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for deleting a category. Validation and adding to the DB is handed off to the
     * model
     *
     * @param $categoryNo       Int of the category to display. Passed to controller by Application.php
     */
    public function delete_category($params)
    {
        $this->body['location'] = 'categories';
        $this->body['content'] = $this->model->getRowFromTable('category', 'categoryNo', $params[0]);

        if(isset($_POST['submit']))
        {
            try
            {
                if ($this->model->deleteCategory($params[0]))
                {
                    header('Location: ' . URL_SUB_DIR . '/admin/list_categories');
                }
            }
            catch (\Exception $e)
            {
                if ($e->getCode() == 23000) // Integrity constraint
                {
                    $this->body['error'] = 'Deletion failed; category in use by a product!';
                }
                else
                {
                    throw $e;
                }
            }
        }

        $this->render('Admin', 'delete_category');
    }
}
