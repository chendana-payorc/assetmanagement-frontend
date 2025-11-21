<input type="hidden" name="id" id="dept_id" value="<?= esc($asset['id'] ?? '') ?>">
<!-- 
<?php print_r($asset) ?>
 -->
<div class="form-group mb-3">
    <label class="required">Model<span style="color:red;font-weight:700;">*</span></label>
    <input class="form-control" type="text" name="model" id="dept_model"
        value="<?= esc($asset['model'] ?? '') ?>" placeholder="Enter Model" required>
</div>

<div class="form-group mb-3">
    <label class="required">Name<span style="color:red;font-weight:700;">*</span></label>
    <input class="form-control" type="text" name="name" id="dept_name"
        value="<?= esc($asset['name'] ?? '') ?>" placeholder="Enter Name" required>
</div>
<div class="row">
        <div class="col-sm-6 form-group">
    <label class="required">Count<span style="color:red;font-weight:700;">*</span></label>
    <input class="form-control" type="number" name="count" id="dept_count"
        value="<?= esc($asset['count'] ?? '') ?>" placeholder="Enter Count" required>
</div>
<div class="col-sm-6 form-group">
            <label class="required">Price<span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control" type="number" name="price" id="dept_price" placeholder="Enter Price" required  value="<?= esc($asset['price'] ?? '') ?>">
        </div>
        </div>
        <div class="row">

<!-- Asset Category -->
<div class="col-sm-6 form-group">
    <label>Asset Category <span style="color:red;font-weight:700;">*</span></label>

    <select name="category_id" id="categorySelect" class="form-select" required>
        <option value="">Select Category</option>

        <?php foreach ($categories as $cat): ?>
            <option value="<?= esc($cat['id']) ?>"
                <?= (!empty($asset['category_id']) && $asset['category_id'] === $cat['id']) ? 'selected' : '' ?>>
                <?= esc($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Asset Supplier -->
<div class="col-sm-6 form-group">
    <label>Asset Supplier <span style="color:red;font-weight:700;">*</span></label>

    <select name="supplier_id" id="supplierSelect" class="form-select" required>
        <option value="">Select Supplier</option>

        <?php foreach ($suppliers as $sup): ?>
            <option value="<?= esc($sup['id']) ?>"
                <?= (!empty($asset['supplier_id']) && $asset['supplier_id'] === $sup['id']) ? 'selected' : '' ?>>
                <?= esc($sup['supplier_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

</div>

<div class="form-group mb-3">
    <label class="required">Description</label>
    <textarea class="form-control" name="description" id="dept_des"
        placeholder="Enter Description"><?= esc($asset['description'] ?? '') ?></textarea>
</div>
