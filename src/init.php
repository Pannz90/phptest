<?php

include_once(__APP__.'/classes/database.php');
include_once(__APP__.'/classes/product.php');
include_once(__APP__.'/classes/productType.php');
include_once(__APP__.'/classes/productAttributes.php');

// Starting php SESSION
if (!isset($_SESSION)) {
    session_start();
}

// Setting easy CSRF token protection
if (!isset($_SESSION['token'])) {
	$token = md5(uniqid(rand(), TRUE));
	$_SESSION['token'] = $token;
	$_SESSION['token_time'] = time();
} else {
	$token = $_SESSION['token'];
}

if( isset($_SESSION['alerts']) && !empty($_SESSION['alerts']) ){
    $alerts = $_SESSION['alerts'];
}

$config = include_once('config.php');

$db = new Database($config["db"]);
$db->getConnection();

/**
 * Install database if table doesn't exists
 */
$db->query("SELECT 1 FROM `product` LIMIT 1");
if( !$db->execute() ){
    /**
     * Create product_type table
     */
    $db->query("
    CREATE TABLE `".$config["db"]["dbname"]."`.`product_type` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(30) NOT NULL,
        PRIMARY KEY (`id`)
    )");
    $db->execute();

    /**
     * Create product_attributes table
     */
    $db->query("
    CREATE TABLE `".$config["db"]["dbname"]."`.`product_attributes` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `type_id` INT(10) UNSIGNED NOT NULL ,
        `code` VARCHAR(30) NOT NULL,
        `name` VARCHAR(30) NOT NULL,
        `comment` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`type_id`) REFERENCES product_type (`id`) ON DELETE CASCADE
    )");
    $db->execute();

    /**
     * Create product table
     */
    $db->query("
    CREATE TABLE `".$config["db"]["dbname"]."`.`product` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `sku` VARCHAR(30) NOT NULL,
        `name` VARCHAR(30) NOT NULL,
        `price` decimal(6,2) NOT NULL,
        `type` INT(10) UNSIGNED NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`type`) REFERENCES product (`id`) ON DELETE CASCADE,
        UNIQUE KEY(`sku`)
    )");
    $db->execute();

    /**
     * Create product_attributes_value table
     */
    $db->query("
    CREATE TABLE `".$config["db"]["dbname"]."`.`product_attributes_value` (
        `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `product_id` INT(10) UNSIGNED NOT NULL,
        `attribute_id` INT(10) UNSIGNED NOT NULL,
        `value` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`product_id`) REFERENCES product (`id`) ON DELETE CASCADE,
        FOREIGN KEY (`attribute_id`) REFERENCES product_attributes (`id`) ON DELETE CASCADE,
        UNIQUE KEY (product_id, attribute_id)
    );
    CREATE UNIQUE INDEX product_attributes_value_UNIQUE_INDEX ON product_attributes_value (product_id,attribute_id);
    ");
    $db->execute();

    /**
     * Insert types sample data
     */
    $db->query("
    INSERT INTO product_type (id, name) VALUES
        (1,'DVD-disk'),
        (2,'Book'),
        (3,'Furniture')
    ");
    $db->execute();

    /**
     * Insert attributes sample data
     */
    $db->query("
    INSERT INTO product_attributes (id, type_id, code, name, comment) VALUES
        (1, 1, 'size', 'Size', 'Please provide size in MB'),
        (2, 2, 'weight', 'Weight', 'Please provide weight in Kg'),
        (3, 3, 'dimensions', 'Dimensions', 'Please provide dimensions in H x W x L')
    ");
    $db->execute();

    /**
     * Insert products sample data
     */
    $db->query("
    INSERT INTO product (id, sku, name, price, type)
        VALUES (NULL,'P001','Acme DISC', '1.00', 1);
    INSERT INTO product_attributes_value (id, product_id, attribute_id, value)
        VALUES (NULL,LAST_INSERT_ID(), 2, '1780');
    ");
    $db->execute();

    $db->query("
    INSERT INTO product (id, sku, name, price, type)
        VALUES (NULL,'P002','War and Peace', '20.00', 2);
    INSERT INTO product_attributes_value (id, product_id, attribute_id, value)
        VALUES (NULL,LAST_INSERT_ID(), 2, '1.2');
    ");
    $db->execute();

    $db->query("
    INSERT INTO product (id, sku, name, price, type)
        VALUES (NULL,'P003','Chair', '40.00', 3);
    INSERT INTO product_attributes_value (id, product_id, attribute_id, value)
        VALUES (NULL,LAST_INSERT_ID(), 3, '80x30x40');
    ");
    $db->execute();
}
