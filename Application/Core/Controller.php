<?php
/**
 * This is the controller superclass, primarily consisting of the render and sanitise methods.
 *
 * Date: 10th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class Controller
{
    protected $model;
    protected $permission = FALSE;
    protected $body = array();

    /** ------------------------------------------------------------------------------------------------------
     * Setup model for this controller
     *
     * @param $model    Instantiate the associated model for this controller
     */
    function __construct($model)
    {
        $this->model = $model;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns the current permission for the given controller
     *
     * @return    Boolean defining whether or not we have permission to view the controller
     */
    public function getPermission()
    {
        return $this->permission;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the main rendering of each page by adding the header and footer template includes either side
     * of the main view file being rendered. This function also sets default components of the $body variable
     * that views utilise as well as recursively sanitising the $body var prior to it being exposed to views.
     *
     * @param $viewDir       String of the subdirectory containing the view file inside the View folder
     * @param $viewFile      String of the view file inside the view subdirectory
     */
    protected function render($viewDir, $viewFile)
    {
        // It's bad practice to expose variables in the session super global directly to the view
        $this->body['cart'] = $_SESSION['cart'];

        // Categories are always exposed to the view to generate the shop drop down etc
        $this->body['categories'] = $this->model->getAllFromTable('category');

        if (isset($_SESSION['username']))
        {
            $this->body['username'] = $_SESSION['username'];
            $this->body['userLevel'] = $_SESSION['userLevel'];
        }
        else
        {
            $this->body['username'] = 'Guest';
            $this->body['userLevel'] = 2; // Guest userLevel
        }

        // Sanitise the $body array using the recursive sanitise() method before exposing the data to the
        // view
        $body = $this->sanitise($this->body);

        require VIEW_DIR . 'Templates' . DS . 'header.php';
        require VIEW_DIR .  $viewDir   . DS .  $viewFile . '.php';
        require VIEW_DIR . 'Templates' . DS . 'footer.php';

        unset($body);
    }



    /** ------------------------------------------------------------------------------------------------------
     * This method does HTML entity encoding to data exposed to views through the body variable.
     * This function is recursive and designed to handle multi-dimensional arrays .
     *
     * @param  $input     Array of content to be sanitised recursively
     *
     * @return $input     The now htmlentities encoded value(s).
     */
    private function sanitise($input)
    {
        if (is_array($input))
        {
            // Foreach array
            foreach ($input as $index => $value)
            {
                $input[$index] = $this->sanitise($value);
            }
        }
        else if (is_string($input))
        {
            $input = htmlentities($input);
        }

        return $input;
    }
}
