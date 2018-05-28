<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Defining root folder
define('__ROOT__', __DIR__.'/..');
define('__APP__', __DIR__.'/../src');

// Defining Http request route
$tokens = explode('/', $_GET['q']);

// Start the app
include(__APP__.'/init.php');

switch ($tokens[0]) {

	case '':
		// Instantiate product model
		$productModel = new Product($db);
		$products = $productModel->getProductCollection();
		$page_title = "Product List";

		include_once(__APP__."/view/list.php");
        break;

    case 'product':
		// If is invalid URL
		if( isset($tokens[2]) && $tokens[2] != "" && $tokens[2] != "delete" ){
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: /product/".$tokens[1]."/");
			exit;
		}

		$update = FALSE;
		$page_title = "Product Add";
		if( // If is sent with product id
			isset($tokens[1]) && $tokens[1] != "" && is_numeric($tokens[1])
		){
			$update = TRUE;
			// Instantiate product model
			$productModel = new Product($db);
			$product = $productModel->getProductById( $tokens[1] );

			if( isset($tokens[2]) && $tokens[2] == "delete" ){
				$sku = $product["sku"];

				// Setting message
				if ( $productModel->deleteProduct($tokens[1]) ){
					$_SESSION['alerts'][] = [
						"status" => "success",
						"message" => "Product with sku ".$sku." has been removed!",
					];
				} else {
					$_SESSION['alerts'][] = [
						"status" => "danger",
						"message" => "Something went wrong while delete product with ".$sku."!",
					];
				}

				// Set HTTP code and redirect to list
				header("HTTP/1.0 202");
				header("Location: /");
				exit;
			}

			$page_title = "Update ".$product["sku"]." product";

		} else if ( // If save form is submitted
			isset($tokens[1]) && $tokens[1] == "save" && isset($_POST["csrf"])
		){
			if( $_POST["csrf"] != $token){
				header("HTTP/1.0 403 Forbidden");
				header("Location: /product/");
				exit;
			}

			// TODO:: Validate data from POST
			$data = [
				"id" => isset($_POST["id"]) && $_POST["id"] != "" ? $_POST["id"]: NULL,
				"sku" => isset($_POST["sku"]) && $_POST["sku"] != "" ? $_POST["sku"]: NULL,
				"name" => isset($_POST["name"]) && $_POST["name"] != "" ? $_POST["name"]: NULL,
				"price" => isset($_POST["price"]) && $_POST["price"] != "" ? $_POST["price"]: NULL,
				"type" => isset($_POST["type"]) && $_POST["type"] != "" ? $_POST["type"]: NULL,
				"attributes" => isset($_POST["attributes"]) && !empty($_POST["attributes"]) ? $_POST["attributes"]: NULL,
			];

			// Instantiate product model and save data
			$productModel = new Product($db);
			$productModel->setData($data);
			$productModel->save();

			// Setting message
			$_SESSION['alerts'][] = [
				"status" => "success",
				"message" => "Product with sku ".$productModel->getData("sku")." saved correctly!",
			];
			header("HTTP/1.0 202");
			header("Location: /product/".$productModel->getData("id")."/");
			exit;
		}

		// If product doesn't exist show 404
		if( isset($tokens[1]) && $tokens[1] != "" && !isset($product["id"]) ){
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
			header("Status: 404 Not Found");
			echo "Product with id ".$tokens[1]." doesn't exists.";
			exit;
		}

		// Instantiate product attributes model
		$attributesModel = new ProductAttributes($db);

		// Instantiate product type model and loading all product types
		$typesModel = new ProductType($db, $attributesModel);
		$types = $typesModel->getProductTypes();

		include_once(__APP__."/view/add.php");
        break;

    default:
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        header("Status: 404 Not Found");
        $_SERVER['REDIRECT_STATUS'] = 404;
		echo "Page not found 404.";
}
