<?php
    include_once(__APP__.'/view/partials/list-actions.php');
    include('partials/header.php');
?>
<div class="container">
    <div class="row">
        <?php foreach ($products as $product) { ?>
            <div class="col-md-3 my-4">
                <div class="card">
                    <div class="card-header">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="massAction[]" class="custom-control-input" value="<?php echo $product["id"]; ?>" id="massAction<?php echo $product["id"]; ?>">
                            <label class="custom-control-label" for="massAction<?php echo $product["id"]; ?>">Select</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><?php echo $product["sku"]; ?></p>
                        <p><?php echo $product["name"]; ?></p>
                        <p><?php echo number_format( $product["price"] , 2 , "," , "." ); ?> €</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($product["attributes"] as $attr) { ?>
                            <li class="list-group-item"><?php echo $attr["name"].": ".$attr["value"]; ?></li>
                        <?php } ?>
                    </ul>
                    <div class="card-body">
                        <a href="/product/<?php echo $product["id"]; ?>/" class="btn btn-primary btn-block" >Edit product</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <hr class="my-4">
    <ul class="list-inline">
        <li class="list-inline-item"><a id='CheckAll_massAction' href=''>Check All</a></li>
        <li class="list-inline-item">|</li>
        <li class="list-inline-item"><a id='UncheckAll_massAction' href=''>Uncheck All</a></li>
    </ul>
</div>
<?php
    include('partials/footer.php');
?>
