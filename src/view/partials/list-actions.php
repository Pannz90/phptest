<?php ob_start(); ?>

<div class="form-inline float-right">
    <div class="form-group mr-2">
        <select class="form-control" id="massActionSelect">
            <option>Mass Action</option>
            <option value="delete">Mass Delete Action</option>
        </select>
    </div>
    <div class="form-group mr-2">
        <button type="submit" class="btn btn-secondary" disabled>Apply</button>
    </div>
    <div class="form-group">
        <a href="/product/" class="btn btn-primary">Add new</a>
    </div>
</div>

<?php $actionsBlock = ob_get_clean(); ?>
