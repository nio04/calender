<?php

namespace App\Traits;

use PDO;
use PDOException;
use Exception;

/**
 * Trait DatabaseTrait
 *
 * Provides methods to interact with the database, including executing SQL queries and performing complex join operations.
 */
trait Database {
  use Connect;

  /**
   * init db connection on DbConnectTrait. 
   * Now, we dont need to init the PDO every time, we need to use database
   */
  function __construct() {
    $this->connect();
  }

  /**
   * Executes a SQL query with optional parameters and returns the result based on the type of query.
   *
   * @param string $sql The SQL query to be executed. This can be any valid SQL command (SELECT, INSERT, UPDATE, DELETE).
   * @param array $params An associative array of parameters to bind to the SQL query. The keys should be the parameter placeholders used in the SQL query, and the values are the actual values to bind.
   * 
   * @return mixed The return value depends on the type of SQL query:
   *               - For SELECT queries: Returns an array of objects where each object represents a row from the result set.
   *               - For INSERT queries: Returns the last inserted ID.
   *               - For UPDATE or DELETE queries: Returns the number of affected rows.
   *               - For other query types: Returns `true` on success.
   * 
   * @throws Exception If there's an error executing the query, an exception is thrown with an error message.
   * 
   * @example
   * ```php
   * $sql = "SELECT * FROM users WHERE id = :id";
   * $params = [':id' => 1];
   * $result = $this->query($sql, $params);
   * ```
   * In this example, the `query` method executes a SELECT statement, binding the value `1` to the `:id` placeholder.
   */
  public function query(string $sql, array $params = []) {
    try {
      $stmt = $this->getDb()->prepare($sql);

      foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
      }

      $stmt->execute();

      $queryType = strtolower(explode(' ', trim($sql))[0]);

      if ($queryType === 'select') {
        return $stmt->fetchAll(PDO::FETCH_OBJ);
      } elseif ($queryType === 'insert') {
        return $this->getDb()->lastInsertId();
      } elseif ($queryType === 'update' || $queryType === 'delete') {
        return $stmt->rowCount();
      } else {
        return true;
      }
    } catch (PDOException $e) {
      throw new Exception("Database query error: " . $e->getMessage());
    }
  }

  /**
   * Executes a complex join query based on multiple parameters.
   *
   * @param array $params An associative array that defines the following:
   *    - 'tables' (array): A list of table names to be joined.
   *    - 'joinConditions' (array): An array of conditions for the joins. These should match the order of tables.
   *    - 'selectColumns' (array|string): The columns to select. This can be an array of column names or a string (e.g., '*').
   *    - 'whereConditions' (array): An array of conditions to filter the results (optional).
   *    - 'orderBy' (string): A column or expression to order the results by (optional).
   *    - 'limit' (string): A limit on the number of rows to return (optional).
   *    - 'bindings' (array): An associative array of placeholders and values to bind to the WHERE conditions (optional).
   * 
   * @return array Returns an array of objects where each object represents a row from the result set.
   * 
   * @throws \InvalidArgumentException If the required 'tables' or 'joinConditions' parameters are not provided.
   * 
   * @example
   * ```php
   * $params = [
   *   'tables' => ['users', 'orders'],
   *   'joinConditions' => ['users.id = orders.user_id'],
   *   'selectColumns' => ['users.name', 'orders.amount'],
   *   'whereConditions' => ['orders.status = :status'],
   *   'orderBy' => 'orders.created_at DESC',
   *   'limit' => '10',
   *   'bindings' => [':status' => 'completed']
   * ];
   * $results = $this->joinQuery($params);
   * ```
   * In this example, the `joinQuery` method performs a join between `users` and `orders` tables, selecting the `name` from `users` and `amount` from `orders` for orders with a status of `completed`.
   */
  public function joinQuery($params) {
    // Set default values for optional parameters
    $tables = $params['tables'] ?? [];
    $joinConditions = $params['joinConditions'] ?? [];
    $selectColumns = $params['selectColumns'] ?? '*';
    $whereConditions = $params['whereConditions'] ?? [];
    $orderBy = $params['orderBy'] ?? '';
    $limit = $params['limit'] ?? '';
    $bindings = $params['bindings'] ?? [];

    // Validate required parameters
    if (empty($tables) || empty($joinConditions)) {
      throw new \InvalidArgumentException('Tables and joinConditions are required parameters.');
    }

    // Build the SELECT clause
    $columns = is_array($selectColumns) ? implode(', ', $selectColumns) : $selectColumns;

    // Start building the SQL query
    $sql = "SELECT $columns FROM " . array_shift($tables);

    // Add join conditions
    foreach ($tables as $index => $table) {
      $sql .= " INNER JOIN $table ON " . $joinConditions[$index];
    }

    // Add WHERE conditions if provided
    if (!empty($whereConditions)) {
      $sql .= " WHERE " . implode(' AND ', $whereConditions);
    }

    // Add ORDER BY clause if provided
    if (!empty($orderBy)) {
      $sql .= " ORDER BY $orderBy";
    }

    // Add LIMIT clause if provided
    if (!empty($limit)) {
      $sql .= " LIMIT $limit";
    }

    // Prepare the SQL statement
    $stmt = $this->getDb()->prepare($sql);

    // Bind parameters if any are used in the WHERE conditions
    foreach ($bindings as $placeholder => $value) {
      $stmt->bindValue($placeholder, $value);
    }

    // Execute the SQL statement
    $stmt->execute();

    // Return the results as an associative array
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
}
