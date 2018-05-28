<?php ob_start(); ?>

<div class="form-inline float-right">
    <div class="form-group mr-2">
        <a href="/" class="btn btn-secondary">Back</a>
    </div>
    <?php if( $update ): ?>
    <div class="form-group mr-2">
        <!-- Pending prompt confirm question -->
        <a href="/product/<?php echo $tokens[1]; ?>/delete/" class="btn btn-danger">Remove</a>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" id="saveButton">Save</button>
    </div>
</div>

<?php $actionsBlock = ob_get_clean(); ?>
