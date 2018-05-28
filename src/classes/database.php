<?php
/**
 * Database connection class
 */

class Database
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var PDOStatement
     */
    private $_stmt;

    /**
    * @var PDO
    */
    private $db_conn;

    /**
     * @param array $config Array with Mysql connection parameters
     */
    public function __construct( array $config )
    {
        $this->config = $config;
        $this->getConnection();
    }

    /**
     * Connect to mysql
     *
     * @return PDO $db_conn
     */
    public function getConnection()
    {
        try {
            $this->db_conn = new PDO("mysql:host=" . $this->config["hostname"] . ";dbname=" . $this->config["dbname"], $this->config["username"], $this->config["password"]);
        } catch (PDOException $exception) {
            echo "Database Connection Error: " . $exception->getMessage();
        }
        return $this->db_conn;
    }

    /**
     * Prepare a new statement
     *
     * @param string $query Mysql statement
     *
     * @return $this
     */
    public function query($query)
    {
        $this->_stmt = $this->db_conn->prepare($query);
        return $this;
    }

    /**
     * Add param value
     *
     * @param string $param
     * @param string|int| $value
     * @param string $type
     *
     * @return void
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->_stmt->bindValue($param, $value, $type);
    }

    /**
     * Execute statement
     *
     * @return bool
     */
    public function execute()
    {
        return $this->_stmt->execute();
    }

    /**
     * Fetch all result statement
     *
     * @return array
     */
    public function resultSet()
    {
        $this->execute();
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get row count
     *
     * @return int
     */
    public function rowCount()
    {
        return $this->_stmt->rowCount();
    }

    /**
     * Get single row value
     *
     * @return array
     */
    public function single()
    {
        $this->execute();
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get last insert id
     *
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->db_conn->lastInsertId();
    }
}
