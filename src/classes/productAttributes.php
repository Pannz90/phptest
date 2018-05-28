<?php

class ProductAttributes
{
    /**
     * @var Database Database connection instance
     */
    private $db_conn;

    /**
     * @var string Database tablename
     */
    private $table_name = "product_attributes";

    /**
     * @param string $ejemplo
     */
    public function __construct( Database $db )
    {
        $this->db_conn = $db;
    }

    /**
     * @return array
     */
    public function getAttributesByType( int $type)
    {
        $this->db_conn->query("SELECT * FROM " . $this->table_name . " WHERE type_id = ".$type." GROUP BY id");

        return $this->db_conn->resultSet();
    }

}
