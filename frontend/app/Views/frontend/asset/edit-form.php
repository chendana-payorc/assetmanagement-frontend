<input type="hidden" name="id" id="dept_id" value="<?= esc($asset['id'] ?? '') ?>">
<!-- 
<?php print_r($asset) ?>
 -->
<div class="form-group mb-3">
    <label class="required">Model</label>
    <input class="form-control" type="text" name="model" id="dept_model"
        value="<?= esc($asset['model'] ?? '') ?>" placeholder="Enter Model" required>
</div>

<div class="form-group mb-3">
    <label class="required">Name</label>
    <input class="form-control" type="text" name="name" id="dept_name"
        value="<?= esc($asset['name'] ?? '') ?>" placeholder="Enter Name" required>
</div>

<div class="form-group mb-3">
    <label class="required">Count</label>
    <input class="form-control" type="number" name="count" id="dept_count"
        value="<?= esc($asset['count'] ?? '') ?>" placeholder="Enter Count" required>
</div>

<div class="form-group mb-3">
    <label class="required">Description</label>
    <textarea class="form-control" name="description" id="dept_des"
        placeholder="Enter Description"><?= esc($asset['description'] ?? '') ?></textarea>
</div>
