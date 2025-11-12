<input type="hidden" name="id" id="dept_id" value="<?= esc($asset['id'] ?? '') ?>">

<div class="form-group mb-3">
    <label class="required">Name <span style="color:red;font-weight:700">*</span></label>
    <input class="form-control" type="text" name="name" id="dept_name"
        value="<?= esc($asset['name'] ?? '') ?>" placeholder="Enter Name" required>
</div>

<div class="form-group mb-3">
    <label>Status</label>
    <select class="form-select" name="status" id="dept_status">
        <option value="1" <?= isset($asset['status']) && $asset['status'] == 1 ? 'selected' : '' ?>>Active</option>
        <option value="0" <?= isset($asset['status']) && $asset['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
    </select>
</div>
