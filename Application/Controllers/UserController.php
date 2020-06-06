<?php
require_once APP_DIR . '/Core/Controller.php';

/**
 * This is the user controller which handles registration of new users and the logging in and out of registered
 * users
 *
 * Date: 11th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class UserController extends Controller
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
     * Simply forwards anyone landing on the index page to login page
     */
    public function index()
    {
        header('Location: ' . URL_SUB_DIR . '/user/login');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for user registration, validation is handed off to the model
     */
    public function register()
    {
        $this->body['location'] = 'register';

        if(isset($_POST['submit']))
        {
            $result = $this->model->addUser($_POST);

            if (!$result['success'])
            {
                $this->body['error'] = $result['error'];
            }
            else
            {
                $_SESSION['userNo'] = $result['userNo'];
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['userLevel'] = 1;

                header('Location: ' . URL_SUB_DIR . '/');
            }
        }

        $this->render('User', 'register');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for user login and associated SESSION details, validation is handed off to
     * the model
     */
    public function login()
    {
        $this->body['location'] = 'login';

        if(isset($_POST['submit']))
        {
            $result = $this->model->validateLogin($_POST);

            if($result['success'])
            {
                $_SESSION['userNo'] = $result['userNo'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['userLevel'] = $result['userLevel'];

                if ($result['userLevel'] == 0)
                {
                    header('Location: ' . URL_SUB_DIR . '/admin');
                }
                else
                {
                    header('Location: ' . URL_SUB_DIR . '/');
                }
            }
            else
            {
                $this->body['message'] = $result['message'];
            }
        }

        $this->render('User', 'login');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Destroys the session upon logout and then redirects the user to the homepage
     */
    public function logout()
    {
        session_destroy();
        header('Location: ' . URL_SUB_DIR . '/');
    }
}
