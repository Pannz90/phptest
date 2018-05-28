<?php

class ProductType
{
    /**
     * @var Database Database connection instance
     */
    private $db_conn;

    /**
     * @var string Database tablename
     */
    private $table_name = "product_type";

    /**
     * @param string $ejemplo
     */
    public function __construct( Database $db, ProductAttributes $attributesModel)
    {
        $this->db_conn = $db;
        $this->attributesModel = $attributesModel;
    }

    /**
     * @return array
     */
    public function getProductTypes()
    {
        $this->db_conn->query("SELECT * FROM " . $this->table_name . " GROUP BY id");

        return $this->db_conn->resultSet();
    }
}
