<?php
/**
 * This class is the core of the MVC framework. It processes the url handing off responsibility to the correct
 * controller, plus the relevant method inside the controller and any associated method params. Also handles
 * low level error codes and exceptions
 *
 * Date: 10th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class Application
{
    private $url;

    /** ------------------------------------------------------------------------------------------------------
     * Start the session and do basic url splitting and sanitation since some parts of the urls will be passed
     * as variables to the function in the controller
     */
    public function __construct()
    {
        // Session is always initialised; even guests have the ability to add items to the cart. Obviously in
        // the real world we'd be required by law to have the user accept cookies, said acceptance would be
        // stored in the session
        session_start();

        // If cart array isn't already in session global, create it
        if (!isset($_SESSION['cart']))
        {
            $_SESSION['cart'] = array();
        }

        $this->url = '';

        // If the httpd's PATH_INFO directive is enabled, get the current URL
        if (isset($_SERVER['REQUEST_URI'])) {
            // Trim the leading and trailing forward slashes from the url
            $this->url = rtrim(ltrim($_SERVER['REQUEST_URI'], '/'), '/');

            // Strip PHP and HTML tags from the url, allowing forward slash deliminator
            $this->url = strip_tags($this->url);

            // And explode (at each forward slash) what remains into an array ready to be processed as
            // controller / method / method variables
            $this->url = explode('/', $this->url);
        }

        $this->handleUrl();

    }



    /** ------------------------------------------------------------------------------------------------------
     * Handle what happens with the URL for handing off to the controller
     */
    private function handleUrl()
    {
        // If $url is still empty we should be at the home page
        if ($this->url[0] == '')
        {
            // So use the Shop controller as we don't want a Header('Location: x') redirect on the home page
            // because that's just bad practice and bad for SEO too.
            require_once APP_DIR . 'Models' . DS . 'ShopModel.php';
            $shopModel = New ShopModel();

            require_once APP_DIR . 'Controllers' . DS . 'ShopController.php';
            $shopController = New ShopController($shopModel);

            $shopController->list_products();
        }
        else
        {
            // Get the controller name from the first element of the url array
            $controller = $this->url[0] ? $this->url[0] : '';
            $controller = ucfirst($controller);

            // And the second part is the method inside the controller, which defaults to index using a
            // unary operator
            $method = isset($this->url[1]) ? $this->url[1] : 'index';

            // Anything else following the method are variables the method handles
            $params = array_slice($this->url, 2);

            // Set the location of the controller, i.e: 'Application/Controllers/ShopController.php'
            $controllerPath = APP_DIR . 'Controllers' . DS . $controller . 'Controller.php';

            if (file_exists($controllerPath))
            {
                try {
                    require_once APP_DIR . 'Models' . DS . $controller . 'Model.php';
                    $modelName = $controller . 'Model';

                    require_once APP_DIR . 'Controllers' . DS . $controller . 'Controller.php';
                    $controllerName = $controller . 'Controller';

                    $controllerObject = new $controllerName(new $modelName);

                    // Controllers have permission criteria (e.g: userLevel = 0 for admin controller) so test
                    // we have access
                    if ($controllerObject->getPermission())
                    {
                        // If we have access pass any params to the relevant method in the relevant controller
                        if (method_exists($controllerObject, $method))
                        {
                            $controllerObject->$method($params);
                        }
                        else
                        {
                            $this->error('404 Not Found', 'The page you requested was not found');
                        }
                    }
                    else
                    {
                        $this->error('403 Forbidden', 'You do not have permission to access this page');
                    }
                }
                // PDO is set to throw exceptions so we'll handle them here as well. In DEV mode we expose a
                // simple message to not give away too much info to potentially nefarious users
                catch (\Exception $e)
                {
                    if (DEV)
                    {
                        $this->error('503 Service Unavailable', $e);
                    }
                    else
                    {
                        $this->error('503 Service Unavailable', 'The service you requested is temporarily unavailable, please try again later');
                    }
                }
            }
            else
            {
                $this->error('404 Not Found', 'The page you requested was not found');
            }
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Constructs a basic error page outside of a controller so the surface exposed in an error code is as
     * simple as possible
     *
     * @param $errorCode    The error code string to use for the header and <h1> tag
     * @param $error        A more complete human-frield message
     */
    private function error($codeCode, $error)
    {
        $body['errorCode'] = $codeCode;
        $body['error'] = $error;

        header('HTTP/1.1 ' . $codeCode);

        require VIEW_DIR . 'Templates' . DS . 'header.php';
        require VIEW_DIR . 'Templates' . DS . 'error.php';
        require VIEW_DIR . 'Templates' . DS . 'footer.php';
    }
}
