<input type="hidden" name="id" value="<?= esc($supplier['id'] ?? '') ?>">

<div class="form-group mb-3">
    <label class="required">Supplier Name <span style="color:red">*</span></label>
    <input class="form-control" type="text" name="supplier_name" value="<?= esc($supplier['supplier_name'] ?? '') ?>" placeholder="Enter Supplier Name" required>
</div>

<div class="form-group mb-3">
    <label class="required">Email <span style="color:red">*</span></label>
    <input class="form-control" type="email" name="email" value="<?= esc($supplier['email'] ?? '') ?>" placeholder="Enter Email" required>
</div>

<div class="form-group mb-3">
    <label class="required">Phone <span style="color:red">*</span></label>
    <input class="form-control" type="text" name="phone" value="<?= esc($supplier['phone'] ?? '') ?>" placeholder="Enter Phone" required>
</div>

<div class="form-group mb-3">
    <label class="required">Organization Name <span style="color:red">*</span></label>
    <input class="form-control" type="text" name="organization_name" value="<?= esc($supplier['organization_name'] ?? '') ?>" placeholder="Enter Organization Name" required>
</div>

<div class="form-group mb-3">
    <label class="required">Address <span style="color:red">*</span></label>
    <textarea name="address" class="form-control" rows="3" placeholder="Enter Address" required><?= esc($supplier['address'] ?? '') ?></textarea>
</div>

<div class="form-group mb-3">
    <label>Status</label>
    <select name="status" class="form-select">
        <option value="active" <?= (isset($supplier['status']) && $supplier['status'] == 'active') ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= (isset($supplier['status']) && $supplier['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
    </select>
</div>

<div class="d-flex justify-content-between mt-3">
    <button class="btn btn-primary" type="submit">Update</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
</div>
