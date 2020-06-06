<?php
/**
 * This is the model superclass and is responsible for opening a database connection and contains basic
 * database methods for getting a row or rows from a table and deleting a row from a table.
 *
 * Date: 10th November 2017
 * @author: Anna Thomas - s4927945
 * Assignment 1 - Bookstore
 */
class Model
{
    protected $db = null;

    /** ------------------------------------------------------------------------------------------------------
     * Discussed with Deniz over email and told I was allowed to use PDO
     */
    function __construct()
    {
        $this->openDatabaseConnection();
    }



    /** ------------------------------------------------------------------------------------------------------
     * Open the database connection with the credentials from Application/config/config.php Discussed with
     * Deniz over email and told I was allowed to use PDO
     */
    private function openDatabaseConnection()
    {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

        $this->db = new PDO('mysql:host=' . DB_IP . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS, $options);
    }



    /** ------------------------------------------------------------------------------------------------------
     * Potentially returns all rows from a given table
     *
     * @param  $table   Name of the table to query
     *
     * @return $rows    All found rows from a given table as an array along with a 'found' boolean declaring
     *                  the outcome
     */
    public function getAllFromTable($table)
    {
        $sql = "SELECT * FROM $table";
        $query = $this->db->prepare($sql);

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
     * Potentially returns a certain row from a given table
     *
     * @param  $table    The name of the table to query
     * @param  $column   The name of the column for the WHERE clause
     * @param  $value    The value for the WHERE clause
     *
     * @return $row      A row (if found) from a given table along with 'found' boolean declaring the outcome
     */
    public function getRowFromTable($table, $column, $value)
    {
        $sql = "SELECT * FROM $table WHERE $column = :value";
        $query = $this->db->prepare($sql);

        $query->bindParam(':value', $value,  PDO::PARAM_STR);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row)
        {
            $row['found'] = TRUE;
        }
        else
        {
            $row['found'] = FALSE;
        }

        return $row;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Potentially returns certain rows from a given table
     *
     * @param  $table    The name of the table to query
     * @param  $column   The name of the column for the WHERE clause
     * @param  $value    The value for the WHERE clause
     *
     * @return $rows     All found rows from a given table that match the WHERE clause, along with a 'found'
     *                   boolean declaring the outcome
     */
    public function getRowsFromTable($table, $column, $value)
    {
        $sql = "SELECT * FROM $table WHERE $column = :value";
        $query = $this->db->prepare($sql);

        $query->bindParam(':value', $value,  PDO::PARAM_STR);
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
     * Potentially deletes a certain row from a given table
     *
     * @param $table  The name of the table to delete a row from
     * @param $column The name of the column for the WHERE clause
     * @param $value  The value for the WHERE clause
     *
     * @return        Boolean of success or failure of query
     */
    public function deleteRowFromTable($table, $column, $value)
    {
        $sql = "DELETE FROM $table WHERE $column = :value";
        $query = $this->db->prepare($sql);

        $query->bindParam(':value', $value,  PDO::PARAM_STR);

        return $query->execute();
    }
}
