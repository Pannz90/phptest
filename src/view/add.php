<?php
    include_once(__APP__.'/view/partials/add-actions.php');
    include('partials/header.php');
?>
<div class="container">
    <form id="productForm" method="post" action="/product/save/">
        <?php if ( $update && isset($tokens[1]) && is_numeric($tokens[1]) ):?>
            <input type="hidden" name="id" id="id" value="<?php echo $tokens[1]; ?>">
        <?php endif;?>
        <input type="hidden" name="csrf" id="csrf" value="<?php echo $token; ?>">
        <div class="form-group row">
            <label for="sku" class="col-sm-2 col-form-label">SKU</label>
            <div class="col-sm-4">
                <input type="text" class="form-control required" required name="sku" id="sku" value="<?php if( $update && isset($product["sku"]) ) { echo $product["sku"]; } ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="name" id="name" value="<?php if( $update && isset($product["name"]) ) { echo $product["name"]; } ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="price" class="col-sm-2 col-form-label">Price</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" name="price" id="price" value="<?php if( $update && isset($product["price"]) ) { echo $product["price"]; } ?>">
                <small>Set price in decimal format 1,00</small>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 col-form-label">Type</label>
            <div class="col-sm-4">
                <select class="form-control" id="type" name="type">
                    <option value="">Select one...</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo $type["id"]; ?>" <?php if( $update && isset($product["type"]) && $product["type"] == $type["id"] ): ?>SELECTED<?php endif; ?>><?php echo $type["name"]; ?></option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>
        <br>
        <h2 class="h4">Product Attributes</h2>
        <hr>
        <div id="attributes">
            <?php if (!$update): ?>
                <div id="noType" class="alert alert-warning" role="alert">Please select product type to add attributes.</div>
            <?php endif; ?>
            <?php foreach ($types as $type): ?>
                <?php $attributes = $attributesModel->getAttributesByType( (int)$type["id"] ); ?>
                <?php foreach ($attributes as $attribute): ?>
                    <div class="form-group row <?php if ( !$update || ($product["type"] != $type["id"]) ): ?>d-none<?php endif; ?> attribute-type-input type-<?php echo $type["id"]; ?>">
                        <label for="attribute-<?php echo $attribute["id"]; ?>" class="col-sm-2 col-form-label"><?php echo $attribute["name"]; ?></label>
                        <div class="col-sm-4">
                            <input type="text" <?php if ( !$update || ($product["type"] != $type["id"]) ): ?>disabled<?php endif; ?> class="form-control" name="attributes[<?php echo $attribute["id"]; ?>]" id="attribute-<?php echo $attribute["id"]; ?>" value="<?php if( $update && isset($product["attributes"][$attribute["code"]]["value"]) ) { echo $product["attributes"][$attribute["code"]]["value"]; } ?>">
                            <?php if(isset($attribute["comment"]) && $attribute["comment"] != ""): ?><small><?php echo $attribute["comment"]; ?></small><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </form>
</div>
<?php
    include('partials/footer.php');
?>
