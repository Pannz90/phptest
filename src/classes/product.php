<?php

class Product
{
    /**
     * @var Database Database connection instance
     */
    private $db_conn;

    /**
     * @var Validator Validator fields instance
     */
    private $validator;

    /**
     * @var string Database tablename
     */
    private $table_name = "product";

    /**
     * Product properties
     * @var array  $properties  Product properties
     */
    protected $fields = [
        "id" => "integer",
        "sku" => "string",
        "name" => "string",
        "price" => "integer",
        "type" => "integer",
        "type_name" => "string",
        "attributes" => "array",
    ];
    protected $id = NULL;
    protected $sku;
    protected $name;
    protected $price;
    protected $type;
    protected $attributes;

    /**
     * @param string $ejemplo
     * @SuppressWarnings(PHPMD.ExitExpression)
     */
    public function __construct( Database $db )
    {
        $this->db_conn = $db;
    }

    /**
     * Set all data directly to the product instance
     *
     * @param array $data
     *
     * @return Product $this
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $value) {
            if( $key == "attributes" ){
                $this->setAttributes($value);
                continue;
            }
            $this->addData($key, $value);
        }

        return $this;
    }

    /**
     * Add single data to the product instance
     *
     * @param string $key
     * @param string|int $value
     *
     * @return Product $this
     */
    public function addData(string $key, $value)
    {
        if( isset($this->fields[$key]) && gettype($value) == $this->fields[$key] ){
            $this->$key = $value;
            return $this;
        } else if ( isset($this->fields[$key]) && $this->fields[$key] == 'integer') {
            $this->$key = (int) $value;
            return $this;
        }

        throw new Exception ('Value '.$key.' should be of type '. $this->fields[$key].'. Type '.gettype($value).' given.');
    }

    /**
     * Set attributes values
     *
     * @param array $attributes
     *
     * @return Product $this
     */
    public function setAttributes(array $attributes)
    {
        if( is_array($attributes) ){
            foreach ($attributes as $attribute => $value) {
                $this->addAttribute($attribute, $value);
            }
            return $this;
        }

        throw new Exception ('Attributes should be sent as array format ["attributeName" => "Value"].');
    }

    /**
     * Add single attribute value
     *
     * @param string $key
     * @param string|int $value
     *
     * @return Product $this
     */
    public function addAttribute(string $key, $value)
    {
        // TODO validate attributes values to be correct format and valid for type product
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     *
     * @return Product $this
     */
    public function getData(string $key)
    {
        if( isset($this->$key) && $this->$key != "" ){
            return $this->$key;
        }

        return NULL;
    }

    /**
     *
     * @param int $id
     *
     * @return $this product
     */
    public function getProductById( int $id )
    {
        $this->id = $id;
        return $this->getProduct("id", $id);
    }

    /**
     *
     * @param string $id
     *
     * @return $this product
     */
    public function getProductBySku( string $sku )
    {
        $this->sku = $sku;
        return $this->getProduct("sku", $sku);
    }

    /**
     * @param string $key ID|NAME|SKU
     * @param int|string $value
     *
     * @return NULL|$this product
     */
    public function getProduct( string $key, $value )
    {
        $sql = "
            SELECT
                ".$this->table_name.".id,
                ".$this->table_name.".sku,
                ".$this->table_name.".name,
                ".$this->table_name.".price,
                ".$this->table_name.".type,
                product_type.name as type_name
            FROM " . $this->table_name . "
            INNER JOIN product_type ON product_type.id = ".$this->table_name.".type
            WHERE " . $this->table_name . ".".$key." = :".$key." LIMIT 1
        ";
        $this->db_conn->query($sql);
        $this->db_conn->bind(':'.$key, $value);

        if( !$data = $this->db_conn->single() ) {
            return "Product with ".$key." ".$value." doesn't exists.";
        }

        $this->db_conn->query("
            SELECT
                product_attributes_value.attribute_id,
                product_attributes_value.value,
                product_attributes.name,
                product_attributes.code
            FROM product_attributes_value
            INNER JOIN product_attributes ON product_attributes.type_id = :type_id
            WHERE product_attributes_value.product_id = :product_id LIMIT 1
        ");
        $this->db_conn->bind(':type_id', $data["type"]);
        $this->db_conn->bind(':product_id', $data["id"]);

        // Adding attributes to product select
        foreach ($this->db_conn->resultSet() as $value) {
            $data["attributes"][$value["code"]] = $value;
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getProductCollection()
    {
        $sql = "SELECT id FROM " . $this->table_name . " GROUP BY id";

        $this->db_conn->query($sql);
        if( !$this->db_conn->execute() ) {
            return "Error";
        }

        if( !$collection = $this->db_conn->resultSet()){
            return "Product with ".$key." ".$value." doesn't exists.";
        }

        $products = [];
        foreach ($collection as $key => $data) {
            $products[] = $this->getProductById($data["id"]);
        }

        return $products;
    }

    /**
     * Save or Update Product
     *
     * @return int|bool return product id or false if fails
     */
    public function save()
    {
        $result = [];
        $sql = "INSERT INTO " . $this->table_name . " ( sku, name, price, type)
                VALUES (:sku, :name, :price, :type)
                ON DUPLICATE KEY UPDATE name = :name, price = :price, type = :type
        ";

        $this->db_conn->query($sql);
        $this->db_conn->bind(':sku', $this->sku);
        $this->db_conn->bind(':name', $this->name);
        $this->db_conn->bind(':price', $this->price);
        $this->db_conn->bind(':type', $this->type);

        if ($result[] = $this->db_conn->execute() && $this->db_conn->getLastInsertId() == 0){
            $this->db_conn->query("SELECT id FROM " . $this->table_name . " WHERE sku = :sku");
            $this->db_conn->bind(':sku', $this->sku);
            $this->db_conn->execute();
            $this->id = $this->db_conn->single()["id"];
        } else {
            $this->id = $this->db_conn->getLastInsertId();
        }

        if( isset($this->attributes) && is_array($this->attributes) && !empty($this->attributes) ) {
            foreach ($this->attributes as $key => $value) {
                $result[] = $this->saveAttribute($key, $value);
            }
        }

        return $result;
    }

    /**
     * Remove Product info
     * @param int $value
     * @return int|bool return product id or false if fails
     */
    public function deleteProduct(int $id)
    {
        $this->db_conn->query("DELETE FROM " . $this->table_name . " WHERE id = :id");

        $this->db_conn->bind(':id', $this->id);

        return $this->db_conn->execute();
    }

    /**
     * Save or Update Attribute product value
     *
     * @return void
     */
    protected function saveAttribute(int $attribute, $value)
    {
        $sql = "INSERT INTO product_attributes_value (product_id, attribute_id, value)
                VALUES (:product_id, :attribute_id, :attribute_value)
                ON DUPLICATE KEY UPDATE value = :attribute_value;
        ";
        $this->db_conn->query($sql);
        $this->db_conn->bind(':product_id', $this->id);
        $this->db_conn->bind(':attribute_id', $attribute);
        $this->db_conn->bind(':attribute_value', $value);

        return $this->db_conn->execute();
    }

    /**
     * Delete product function
     *
     * @return bool
     */
    public function delete()
    {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = :id ";

        $this->db_conn->query($sql);
        $this->db_conn->bind(':id', $this->id);

        return $this->db_conn->execute();
    }

}
