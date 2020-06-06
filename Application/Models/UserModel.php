<?php
require_once APP_DIR . '/Core/Model.php';

/**
 * User model, expands upon the base model's functionality to validate registration, add new users and
 * validate login.
 *
 * Date: 11th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class UserModel extends Model
{
    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for user login
     *
     * @param  $post    Post data sent by the form
     *
     * @return $result  A success boolean of the validation along with any error message generated
     */
    public function validateLogin($post)
    {
        $sql = 'SELECT userNo, username, password, userLevel FROM user WHERE username = :username';
        $query = $this->db->prepare($sql);

        $query->bindParam(':username', $post['username'], PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $hashedPassword = crypt($post['password'], '$6$rounds=150000$PerUserCryptoRandomSalt$');

        // Purposefully leaving the failed state ambiguous so as to not inform users if a username is valid
        // or not for security reasons
        if($hashedPassword == $result['password'])
        {
            $result['success'] = TRUE;
        }
        else
        {
            $result['success'] = FALSE;
            $result['message'] = 'Invalid username or password.';
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Adds a new user to the user table of the DB assuming the $post data passes validation
     *
     * @param  $post        Post data sent by the form
     *
     * @return $validation  A success boolean of the validation along with any error message generated
     */
    public function addUser($post)
    {
        $result = $this->validateRegistration($post);

        if ($result['success']) {

            $hashedPassword = crypt($post['password'], '$6$rounds=150000$PerUserCryptoRandomSalt$');

            $sql = 'INSERT INTO user (username, password, firstName, lastName, email, add1, city, county, postcode)
                    VALUES (:username, :password, :firstName, :lastName, :email, :address, :city, :county, :postcode)';

            $query = $this->db->prepare($sql);
            $query->bindParam(':username',  $post['username'],  PDO::PARAM_STR);
            $query->bindParam(':password',  $hashedPassword,    PDO::PARAM_STR);
            $query->bindParam(':firstName', $post['firstName'], PDO::PARAM_STR);
            $query->bindParam(':lastName',  $post['lastName'],  PDO::PARAM_STR);
            $query->bindParam(':email',     $post['email'],     PDO::PARAM_STR);
            $query->bindParam(':address',   $post['address'],   PDO::PARAM_STR);
            $query->bindParam(':city',      $post['city'],      PDO::PARAM_STR);
            $query->bindParam(':county',    $post['county'],    PDO::PARAM_STR);
            $query->bindParam(':postcode',  $post['postcode'],  PDO::PARAM_STR);

            $query->execute();

            $result['userNo'] = $this->db->lastInsertId();
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for new user registration
     *
     * @param  $post    Post data sent by the calling function
     *
     * @return $result  A success boolean of the validation along with any error message generated
     */
    public function validateRegistration($post)
    {
        $result['success'] = FALSE;
        $desiredUsername = $this->getRowFromTable('user', 'username', $post['username']);

        // We're using php 5.3.3 so we'll use trim($foo) == FALSE instead of empty(trim($foo))
        if (trim($post['username']) == FALSE)
        {
            $result['error'] = 'Please enter a username';
        }
        else if (strlen(trim($post['username'])) < 4)
        {
            $result['error'] = 'Username must be at least 4 characters';
        }
        else if ($desiredUsername['found'])
        {
            $result['error'] = 'Username not valid'; // Intentionally ambiguous
        }
        else if (trim($post['password']) == FALSE)
        {
            $result['error'] = 'Please enter a password';
        }
        else if (strlen(trim($post['password'])) < 6)
        {
            $result['error'] = 'Password must be at least 6 characters';
        }
        else if (trim($post['passwordConfirmation']) == FALSE)
        {
            $result['error'] = 'Please enter password confirmation';
        }
        else if (trim($post['password']) != trim($post['passwordConfirmation']))
        {
            $result['error'] = 'Passwords do not match';
        }
        else if (trim($post['firstName']) == FALSE)
        {
            $result['error'] = 'Please enter a first name';
        }
        else if (trim($post['lastName']) == FALSE)
        {
            $result['error'] = 'Please enter a last name';
        }
        else if (trim($post['email']) == FALSE)
        {
            $result['error'] = 'Please enter your email';
        }
        else if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
        {
            $result['error'] = 'Please enter a valid email address';
        }
        else if (trim($post['address']) == FALSE)
        {
            $result['error'] = 'Please enter the first line of your address';
        }
        else if (trim($post['city']) == FALSE)
        {
            $result['error'] = 'Please enter your city';
        }
        else if (trim($post['county']) == FALSE)
        {
            $result['error'] = 'Please enter your county';
        }
        else if (trim($post['postcode']) == FALSE)
        {
            $result['error'] = 'Please enter your postcode';
        }
        else
        {
            $result['success'] = TRUE;
        }

        return $result;
    }
}
