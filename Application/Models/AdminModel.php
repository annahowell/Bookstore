<?php
require_once APP_DIR . '/Core/Model.php';

/**
 * Admin model, expands upon the base model's functionality to allow admins to add, update and delete products
 * and categories. View and search orders and handle image uploads
 *
 * Date: 11th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class AdminModel extends Model
{
    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic and for adding a product to inventory
     *
     * @param  $post          Post data sent by the form
     * @param  $file          File data sent by the form
     *
     * @return $validation    Array containing success boolean, and any error messages from the validation
     *                        method
     */
    public function addProduct($post, $file)
    {
        $validation = $this->validateProduct($post, $file);

        if ($validation['success'])
        {
            $dateModified = date('Y-m-d H:i:s');

            $sql = 'INSERT INTO product (name, author, isbn, description, price, stockLevel, categoryNo, imageName, dateModified)
                    VALUES (:name, :author, :isbn ,:description, :price, :stockLevel, :categoryNo, :imageName, :dateModified)';

            $query = $this->db->prepare($sql);
            $query->bindParam(':name',         $post['name'],            PDO::PARAM_STR);
            $query->bindParam(':author',       $post['author'],          PDO::PARAM_STR);
            $query->bindParam(':isbn',         $post['isbn'],            PDO::PARAM_STR);
            $query->bindParam(':description',  $post['description'],     PDO::PARAM_STR);
            $query->bindParam(':price',        $post['price'],           PDO::PARAM_STR);
            $query->bindParam(':stockLevel',   $post['stockLevel'],      PDO::PARAM_INT);
            $query->bindParam(':categoryNo',   $post['categoryNo'],      PDO::PARAM_INT);
            $query->bindParam(':imageName',    $validation['imageName'], PDO::PARAM_STR);
            $query->bindParam(':dateModified', $dateModified,            PDO::PARAM_STR);

            $query->execute();
        }

        return $validation;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic and for adding a new category
     *
     * @param  $post          Post data sent by the form
     * @param  $file          File data sent by the form
     *
     * @return $validation    Array containing success boolean, and any error messages from the validation
     *                        method
     */
    public function addCategory($post)
    {
        $validation = $this->validateCategory($post);

        if ($validation['success'])
        {
            $sql = "INSERT INTO category (name)
                    VALUES (:name)";

            $query = $this->db->prepare($sql);
            $query->bindParam(':name', $post['name'], PDO::PARAM_STR);

            $query->execute();
        }

        return $validation;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic for updating an existing product
     *
     * @param  $post                Post data sent by the form
     * @param  $file                File data sent by the form
     * @param  $productNo           The product number of the product we're updating
     * @param  $existingImageName   The name of the image currently being used for this product
     *
     * @return $validation          Array containing success boolean, and any error messages from the
     *                              validation method
     */
    public function updateProduct($post, $file, $productNo, $existingImageName)
    {
        $validation = $this->validateProduct($post, $file, False); // This is not a new product

        if ($validation['success'])
        {
            $dateModified = date('Y-m-d H:i:s');

            $sql = 'UPDATE product SET name = :name,
                                     author = :author,
                                       isbn = :isbn,
                                description = :description,
                                      price = :price,
                                 stockLevel = :stockLevel,
                                    removed = :removed,
                                 categoryNo = :categoryNo,
                               dateModified = :dateModified,
                                  imageName = :imageName WHERE productNo = :productNo';

            $query = $this->db->prepare($sql);
            $query->bindParam(':name',         $post['name'],        PDO::PARAM_STR);
            $query->bindParam(':author',       $post['author'],      PDO::PARAM_STR);
            $query->bindParam(':isbn',         $post['isbn'],        PDO::PARAM_STR);
            $query->bindParam(':description',  $post['description'], PDO::PARAM_STR);
            $query->bindParam(':price',        $post['price'],       PDO::PARAM_STR);
            $query->bindParam(':stockLevel',   $post['stockLevel'],  PDO::PARAM_INT);
            $query->bindParam(':removed',      $post['removed'],     PDO::PARAM_INT);
            $query->bindParam(':categoryNo',   $post['categoryNo'],  PDO::PARAM_INT);
            $query->bindParam(':dateModified', $dateModified,        PDO::PARAM_STR);
            $query->bindParam(':productNo',    $productNo,           PDO::PARAM_INT);

            if (isset($validation['imageName']))
            {
                $query->bindParam(':imageName', $validation['imageName'], PDO::PARAM_STR);

                $this->removeImageFile($existingImageName);
            }
            else
            {
                $query->bindParam(':imageName', $existingImageName, PDO::PARAM_STR);
            }

            $query->execute();
        }

        return $validation;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic and DB calls for updating an existing category
     *
     * @param  $post                Post data sent by the form
     * @param  $file                File data sent by the form
     * @param  $categoryNo          The category number of the category we're updating
     *
     * @return $validation          Array containing success boolean, and any error messages from the
     *                              validation method
     */
    public function updateCategory($post, $categoryNo)
    {
        $validation = $this->validateCategory($post); // This is not a new category

        if ($validation['success'])
        {
            $sql = 'UPDATE category SET name = :name WHERE categoryNo = :categoryNo';

            $query = $this->db->prepare($sql);
            $query->bindParam(':name',        $post['name'],        PDO::PARAM_STR);
            $query->bindParam(':categoryNo',  $categoryNo,              PDO::PARAM_INT);

            $query->execute();

        }
        return $validation;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns a list of orders from the placed_order table along with a few details of the ordering user
     *
     * @param $limit     Total number of results to return
     * @param $offset    Offset from which the results are returned
     *
     * @return $rows     Rows (if found) from a given table along with a 'found' boolean declaring outcome
     */
    public function getProducts($limit, $offset)
    {
        $sql = "SELECT * FROM product ORDER BY name ASC LIMIT $limit OFFSET $offset";

        $query = $this->db->prepare($sql);

        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            $rows['found'] = TRUE;
            $rows['pagCount'] = ceil($this->getNum('product') / $limit);
        }
        else
        {
            $rows['found'] = FALSE;
        }

        return $rows;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns a list of orders from the placed_order table along with a few details of the ordering user
     *
     * @return $rows     Rows (if found) from a given table along with a 'found' boolean declaring outcome
     */
    public function getOrders($limit, $offset)
    {
        $sql = "SELECT firstName, lastName, postcode, orderNo, dateOrdered, totalPaid
                  FROM placed_order INNER JOIN user ON placed_order.userNo = user.userNo ORDER BY placed_order.dateOrdered DESC LIMIT $limit OFFSET $offset";

        $query = $this->db->prepare($sql);

        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            $rows['found'] = TRUE;
            $rows['pagCount'] = ceil($this->getNum('placed_order') / $limit);
        }
        else
        {
            $rows['found'] = FALSE;
        }

        return $rows;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns the total number of rows in a given table
     *
     * @param  $table       The table to query
     *
     * @return $result      The number of rows
     */
    private function getNum($table)
    {
        $sql = "SELECT COUNT(*) FROM $table";
        $query = $this->db->prepare($sql);

        $query->execute();

        return $query->fetchColumn();
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns detailed data about a given order, including the user the ordered it etc
     *
     * @param  $orderNo     The order number we're getting detailed info for
     *
     * @return $result      An array containing a boolean of found success along with an array of the user
     *                      details and order details if found
     */
    public function getOrderDetails($orderNo)
    {
        // We want to group multiple SELECT queries in to one transaction to speed up DB calls and enforce
        // continuity
        $this->db->beginTransaction();

        $sql = "SELECT firstName, lastName, email, add1, city, county, postcode, dateOrdered
                  FROM user INNER JOIN placed_order ON placed_order.userNo = user.userNo
                 WHERE placed_order.orderNo = $orderNo";

        $query = $this->db->prepare($sql);
        $query->execute();

        $orderingUser = $query->fetch(PDO::FETCH_ASSOC);

        // -------------------------------------------------------------------------------------------

        $sql = "SELECT name, author, isbn, quantityOrdered, pricePaid
                  FROM order_item INNER JOIN product ON order_item.productNo = product.productNo
                 WHERE order_item.orderNo = $orderNo";


        $query = $this->db->prepare($sql);
        $query->execute();

        $productsOrdered = $query->fetchAll(PDO::FETCH_ASSOC);

        // Commit our grouped SELECT queries as a single transaction
        $this->db->commit();

        if ($orderingUser && $productsOrdered)
        {
            $result['found'] = TRUE;
            $result['user'] = $orderingUser;
            $result['products'] = $productsOrdered;
        }
        else
        {
            $result['found'] = FALSE;
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic and DB calls for searching orders
     *
     * @param  $searchTerm   The term searched for, we're using bind param so happy it'll be sanitised.
     *
     * @return $rows         Array containing found boolean, and any found orders as an array
     */
    public function searchOrder($searchTerm)
    {
        $sql = "SELECT u.firstName, u.lastName, u.postcode, po.orderNo, po.dateOrdered, po.totalPaid
                  FROM placed_order po INNER JOIN user u ON po.userNo = u.userNo
                 WHERE u.firstName     LIKE :searchTerm
                    OR u.lastName      LIKE :searchTerm
                    OR u.postcode      LIKE :searchTerm
                    OR po.orderNo      LIKE :searchTerm
                    OR po.dateOrdered  LIKE :searchTerm
                    OR po.totalPaid    LIKE :searchTerm";

        $query = $this->db->prepare($sql);

        $query->bindParam(':searchTerm',   $searchTerm,   PDO::PARAM_STR);
        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            $rows['found'] = TRUE;
        }
        else
        {
            $rows['found'] = FALSE;
        }

        return $rows;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for the amendment of products
     *
     * @param  $post        Post data sent by the calling function
     * @param  $file        File data sent by the calling function
     * @param  $newProduct  Whether or not this is the validation of a new product
     *
     * @return $validation  A success boolean of the validation along with any error message generated and the
     *                      image name for the product
     */
    private function validateProduct($post, $file, $newProduct = TRUE)
    {
        $result['success'] = FALSE;
        $row = $this->getRowFromTable('category', 'categoryNo', $post['categoryNo']);

        // We're using php 5.3.3 so we'll use trim($foo) === false instead of empty(trim($foo))
        if (trim($post['name']) == FALSE)
        {
            $result['error'] = 'Please enter a valid name';
        }
        else if (trim($post['description']) == FALSE)
        {
            $result['error'] = 'Please enter a valid description';
        }
        else if (!isset($post['price']) || !is_numeric($post['price']) || $post['price'] <= 0)
        {
            $result['error'] = 'Please enter a valid price';
        }
        else if (!isset($post['stockLevel']) || !is_numeric($post['stockLevel']) || $post['stockLevel'] < 0)
        {
            $result['error'] = 'Please enter a valid stock level';
        }
        else if (!$row['found'])
        {
            $result['error'] = 'Please enter a valid category';
        }
        else
        {
            if (is_uploaded_file($file['tmp_name']))
            {
                $result = $this->handleImageUpload($file);
            }
            else if (!$newProduct)
            {
                $result['success'] = TRUE;
            }
            else
            {
                $result['imageName'] = PLACEHOLDER_IMG; // Found in config.php
                $result['success'] = TRUE;
            }
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for the amendment of categories
     *
     * @param  $post        Post data sent by the calling function
     * @param  $file        File data sent by the calling function
     * @param  $newCategory Whether or not this is the validation of a new category
     *
     * @return $validation  A success boolean of the validation along with any error message generated and the
     *                      image name for the category
     */
    private function validateCategory($post)
    {
        $result['success'] = FALSE;

        if (trim($post['name']) == FALSE)
        {
            $result['error'] = 'Please enter a valid name';
        }
        else
        {
            $result['success'] = TRUE;
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple file upload handling for category and product images
     *
     * @param  $file        File data sent by the calling function
     *
     * @return $validation  A success boolean of the validation along with any error message generated and the
     *                      image name generated by the file saving process.
     */
    private function handleImageUpload($file)
    {
        $result['success'] = FALSE;
        $allowedExtensions = array('png', 'jpg', 'jpeg');

        $explodedName = explode('.', $file['name']);
        $fileExtension = strtolower(end($explodedName));
        $imageName = mt_rand(1000000000, 9999999999) . '.' . $fileExtension;

        if (!in_array($fileExtension, $allowedExtensions))
        {
            $result['error'] = 'Invalid file extension';
        }
        else if ($file['size'] > 3072000)
        {
            $result['error'] = 'File size is larger than 3 meg';
        }
        else if(!move_uploaded_file($file['tmp_name'], IMAGE_DIR . $imageName))
        {
            $result['error'] = 'An unhandled error occurred';
        }
        else
        {
            $result['imageName'] = $imageName;
            $result['success'] = TRUE;
        }
        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Deletes a product from the product table, along with it's associated image file, assuming the product
     * has not been used in an order
     *
     * @param  $productNo           The number of the product to be deleted
     * @param  $imageNameToDelete   The name of the file to be deleted upon success of removal from product
     *                              table
     *
     * @return $result              A boolean containing the success or failure of the deletion attempt
     */
    public function deleteProduct($productNo, $imageNameToDelete)
    {
        $result = $this->deleteRowFromTable('product', 'productNo', $productNo);

        if ($result)
        {
            $this->removeImageFile($imageNameToDelete);
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Deletes a category from the category table, along with it's associated image file, assuming a product
     * is not using the category
     *
     * @param  $categoryNo           The number of the category to be deleted
     *
     * @return $result               A boolean containing the success or failure of the deletion attempt
     */
    public function deleteCategory($categoryNo)
    {
        $result = $this->deleteRowFromTable('category', 'categoryNo', $categoryNo);

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Deletes an image file from disk if the filename passes isn't the placeholder image name
     *
     * @param  $imageNameToDelete    The name of the file to be deleted from disk
     */
    private function removeImageFile($imageNameToDelete)
    {
        if ($imageNameToDelete != PLACEHOLDER_IMG)
        {
            @unlink(IMAGE_DIR . $imageNameToDelete);
        }
    }
}
