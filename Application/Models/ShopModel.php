<?php
require_once APP_DIR . '/Core/Model.php';

/**
 * Shop model, expands upon the base model's functionality to allow users to add items to their cart, place
 * orders and search products.
 *
 * Date: 11th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class ShopModel extends Model
{
    /** ------------------------------------------------------------------------------------------------------
     * Queries the product database to return limited ordered products by search term
     *
     * @param $category     The category to search
     * @param $searchTerm   The term to search for
     * @param $orderBy      The column to order by
     * @param $orderDir     The direction to order by
     * @param $limit        The limit of results to return
     * @param $offset       The offset to begin the return of results from
     *
     * @return $rows        The data returned by the query along with a found boolean and potential page count
     */
    public function getLimitedOrderedRows($category, $searchTerm, $orderBy, $orderDir, $limit, $offset)
    {
        // We'll do some further sanity checks on values passed, remembering that the entire url has already
        // been run through strip_tags in Application.php anyway
        if (!in_array($orderBy, array('name', 'price')))
        {
            $orderBy = 'name';
        }

        if ($orderDir != 'ASC')
        {
            $orderDir = 'DESC';
        }

        $sql = "SELECT p.productNo, p.name, p.author, p.price, p.stockLevel, p.imageName
                  FROM product p INNER JOIN category c ON p.categoryNo = c.categoryNo
                 WHERE (p.categoryNo LIKE :category)
                   AND (p.removed = 0)
                   AND (p.name       LIKE :searchTerm
                    OR c.name        LIKE :searchTerm
                    OR p.author      LIKE :searchTerm
                    OR p.isbn        LIKE :searchTerm
                    OR p.description LIKE :searchTerm)
              ORDER BY $orderBy $orderDir
                 LIMIT $limit OFFSET $offset";

        $query = $this->db->prepare($sql);

        $query->bindParam(':category',   $category,   PDO::PARAM_STR);
        $query->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            $rows['found'] = TRUE;
            $rows['pagCount'] = ceil($this->getProductNum($category, $searchTerm) / $limit);
        }
        else
        {
            $rows['found'] = FALSE;
        }

        return $rows;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Queries product database to return no. of values based on search criteria from getLimitedOrderedRows()
     *
     * @param $category     The category to search
     * @param $searchTerm   The term to search for
     *
     * @return              The data returned by query along with a found boolean and potential page count
     */
    private function getProductNum($category, $searchTerm)
    {
        $sql = "SELECT COUNT(*)
                  FROM product p INNER JOIN category c ON p.categoryNo = c.categoryNo
                 WHERE (p.categoryNo LIKE :category)
                   AND (p.removed = 0)
                   AND (p.name       LIKE :searchTerm
                    OR c.name        LIKE :searchTerm
                    OR p.author      LIKE :searchTerm
                    OR p.isbn        LIKE :searchTerm
                    OR p.description LIKE :searchTerm)";
        $query = $this->db->prepare($sql);

        $query->bindParam(':category', $category,  PDO::PARAM_STR);
        $query->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

        $query->execute();

        return $query->fetchColumn();
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic and validation for attempting to add an item to cart
     *
     * @param  $post         Post data sent by the form
     * @param  $productNo    Product number we wish to add to the cart
     *
     * @return $result       Array containing success boolean, any error messages and returned data to be
     *                       processed
     */
    public function addToCart($post, $productNo)
    {
        $result['success'] = FALSE;
        $requestedProduct = $this->getRowFromTable('product', 'productNo', $productNo);

        if (!$requestedProduct['found'])
        {
            $result['error'] = 'Product not found';
        }
        // We'll sanitise the quantity by casting to int
        else if($requestedProduct['stockLevel'] < (int)$post['quantity'])
        {
            $result['error'] = 'Quantity requested exceeded current stock level';
        }
        else
        {
            $result['success'] = TRUE;

            // We'll get these values now in a slightly hacky way, to save calls to the db in the view_cart
            // method in the controller and also for continuity, so the price the customer pays is the price
            // that was advertised when they added it to cart.
            $result['name'] = $requestedProduct['name'];
            $result['price'] = $requestedProduct['price'];
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic and validation for placing an error
     *
     * @param  $cart         Cart data to process when placing the order
     * @param  $totalPaid    Total the user paid for the order
     * @param  $userNo       User number of the customer placing the order
     *
     * @return $result       Array containing success boolean, any error messages and returned data to be
     *                       processed
     */
    public function placeOrder($cart, $totalPaid, $userNo)
    {
        // We're not keeping items added to cart in escrow so we'll do some checks again
        $result['success'] = TRUE;

        // $cart should be sanitised through other functions that added to the server side SESSION var
        foreach ($cart as $cartItem)
        {
            $requestedProduct = $this->getRowFromTable('product', 'productNo', $cartItem['productNo']);

            if (!$requestedProduct['found'])
            {
                $result['error'] = 'Product not found';
                $result['success'] = FALSE;
            }
            else if($requestedProduct['stockLevel'] < $cartItem['quantity'])
            {
                $result['error'] = 'Quantity requested exceeded current stock level';
                $result['success'] = FALSE;
            }
        }

        if ($result['success'])
        {
            $dateOrdered = date('Y-m-d H:i:s');

            // We want to group multiple INSERT queries in to one transaction to speed up DB calls and
            // enforce continuity
            $this->db->beginTransaction();

            $sql = 'INSERT INTO placed_order (userNo, dateOrdered, totalPaid)
                    VALUES (:userNo, :dateOrdered, :totalPaid)';

            $query = $this->db->prepare($sql);
            $query->bindParam(':userNo',      $userNo,      PDO::PARAM_INT);
            $query->bindParam(':dateOrdered', $dateOrdered, PDO::PARAM_STR);
            $query->bindParam(':totalPaid',   $totalPaid,   PDO::PARAM_STR);

            $query->execute();

            $orderNo = $this->db->lastInsertId();

            foreach ($cart as $cartItem)
            {
                // Decrement stock
                $sql = 'UPDATE product SET stockLevel = stockLevel - :quantityOrdered WHERE productNo = :productNo and stockLevel > 0';

                $query = $this->db->prepare($sql);
                $query->bindParam(':productNo',       $cartItem['productNo'], PDO::PARAM_INT);
                $query->bindParam(':quantityOrdered', $cartItem['quantity'],  PDO::PARAM_INT);

                $query->execute();


                // Add ordered items to the order_item table
                $sql = 'INSERT INTO order_item (orderNo, productNo, quantityOrdered, pricePaid)
                        VALUES (:orderNo, :productNo, :quantityOrdered, :pricePaid)';

                $query = $this->db->prepare($sql);
                $query->bindParam(':orderNo',         $orderNo,               PDO::PARAM_INT);
                $query->bindParam(':productNo',       $cartItem['productNo'], PDO::PARAM_INT);
                $query->bindParam(':quantityOrdered', $cartItem['quantity'],  PDO::PARAM_INT);
                $query->bindParam(':pricePaid',       $cartItem['price'],     PDO::PARAM_STR);

                $query->execute();
            }

            // Commit out grouped INSERT queries as a single transaction
            $this->db->commit();

            $_SESSION['cart'] = array();
        }

        return $result;
    }
}
