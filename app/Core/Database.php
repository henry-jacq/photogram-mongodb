<?php

namespace App\Core;

use PDO;

class Database
{
    protected $table = null;
    protected $fetchMode = null;

    public function __construct(public readonly PDO $conn)
    {
        $this->fetchMode = $this->conn::FETCH_ASSOC;
    }

    /**
     * Factory pattern for getting a database connection
     */
    public static function getConnection(PDO $pdo)
    {
        return new static($pdo);
    }

    /**
     * Return a PDO statement object
     */
    public function run($sql, $args = [])
    {
        if (empty($args)) {
            return $this->conn->query($sql);
        }

        $stmt = $this->conn->prepare($sql);

        // Check if args is associative or sequential
        $is_assoc = array_keys($args) !== range(0, count($args) - 1);
        if ($is_assoc) {
            foreach ($args as $key => $value) {
                if (is_int($value)) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value);
                }
            }
            $stmt->execute();
        } else {
            $stmt->execute($args);
        }

        return $stmt;
    }

    public function setTable(string $tableName)
    {
        $this->table = $tableName;
    }

    public function getDB()
    {
        return $this->conn;
    }

    public function raw($sql)
    {
        $this->conn->query($sql);
    }

    public function row($sql, $args = [], $fetchMode = null)
    {
        $results = $this->select($sql, $args, $fetchMode);
        return $results[0] ?? null;
    }

    public function rows($sql, $args = [], $fetchMode = null)
    {
        return $this->select($sql, $args, $fetchMode);
    }

    public function getRowById($id, $param = 'id', $fetchMode = null)
    {
        $conditions = [$param => $id];
        $results = $this->select(['*'], $conditions, null, 1, null, $fetchMode);
        return $results[0] ?? null;
    }

    public function getCount($sql, $args = [])
    {
        $stmt = $this->run($sql, $args);
        return $stmt->rowCount();
    }

    /**
     * Get primary key of last inserted record
     */
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function insert(array $data)
    {
        // Add columns into comma separated string
        $columns = implode(',', array_keys($data));

        // Get values
        $values = array_values($data);

        $placeholders = array_map(function ($val) {
            return '?';
        }, array_keys($data));

        // Convert array into comma separated string
        $placeholders = implode(',', array_values($placeholders));

        $this->run("INSERT INTO $this->table ($columns) VALUES ($placeholders)", $values);

        return $this->lastInsertId();
    }

    public function update(array $data, array $where): int
    {
        // Collect the values from data and where
        $values = [];

        // Setup fields
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = ?,";
            $values[] = $value;
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        // Setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $values[] = $value;
            $i++;
        }

        $stmt = $this->run("UPDATE $this->table SET $fieldDetails WHERE $whereDetails", $values);

        return $stmt->rowCount();
    }

    public function delete(array $where, $limit = 1)
    {
        // Collect the values from collection
        $values = array_values($where);

        // Setup where 
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        // If limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $limit = "LIMIT $limit";
        }

        $stmt = $this->run("DELETE FROM $this->table WHERE $whereDetails $limit", $values);

        return $stmt->rowCount();
    }

    public function deleteById($id)
    {
        $stmt = $this->run("DELETE FROM $this->table WHERE id = ?", [$id]);

        return $stmt->rowCount();
    }

    /**
     * Select records from the table
     * 
     * @param array|string $columns - Columns to retrieve or raw SQL
     * @param array $conditions - Conditions for the query
     * @param string|null $orderBy - Order by clause
     * @param int|null $limit - Limit the number of results
     * @param int|null $offset - Offset for the results
     * @param int|null $fetchMode - Fetch mode
     * 
     * @return array - Fetched records
     */
    public function select($columns = ['*'], array $conditions = [], string $orderBy = null, int $limit = null, int $offset = null, int $fetchMode = null)
    {
        if ($fetchMode === null) {
            $fetchMode = $this->fetchMode;
        }

        // If raw SQL is provided, use it directly
        if (is_string($columns)) {
            $sql = $columns;
            $stmt = $this->run($sql, $conditions);
            return $stmt->fetchAll($fetchMode);
        }

        $columnsString = implode(', ', $columns);
        $sql = "SELECT $columnsString FROM $this->table";

        // Process conditions
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . $this->buildConditions($conditions);
        }

        // Add order by clause if provided
        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        // Add limit and offset if provided
        if ($limit) {
            $sql .= " LIMIT $limit";
            if ($offset) {
                $sql .= " OFFSET $offset";
            }
        }

        // Prepare and execute the statement
        $stmt = $this->conn->prepare($sql);

        // Bind values for where conditions
        $this->bindValues($stmt, $conditions);

        $stmt->execute();

        // Return fetched results
        return $stmt->fetchAll($fetchMode);
    }

    /**
     * Build conditions string from associative array
     * 
     * @param array $conditions - Conditions for the query
     * 
     * @return string - SQL conditions string
     */
    private function buildConditions(array $conditions)
    {
        $parts = [];

        foreach ($conditions as $key => $value) {
            if (is_array($value) && in_array(strtoupper($key), ['AND', 'OR'])) {
                $subParts = [];
                foreach ($value as $subKey => $subValue) {
                    $subParts[] = "$subKey = :$subKey";
                }
                $parts[] = '(' . implode(" $key ", $subParts) . ')';
            } else {
                $parts[] = "$key = :$key";
            }
        }

        return implode(' AND ', $parts);
    }

    /**
     * Bind values to the prepared statement
     * 
     * @param \PDOStatement $stmt - PDO statement object
     * @param array $conditions - Conditions for the query
     */
    private function bindValues(\PDOStatement $stmt, array $conditions)
    {
        foreach ($conditions as $key => $value) {
            if (is_array($value) && in_array(strtoupper($key), ['AND', 'OR'])) {
                foreach ($value as $subKey => $subValue) {
                    if (is_int($subValue)) {
                        $stmt->bindValue(":$subKey", $subValue, PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":$subKey", $subValue);
                    }
                }
            } else {
                if (is_int($value)) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value);
                }
            }
        }
    }
}
