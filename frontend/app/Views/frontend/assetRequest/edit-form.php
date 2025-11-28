<input type="hidden" name="id" id="req_id" value="<?= esc($request['id'] ?? '') ?>">

<div class="form-group mb-3">
    <label class="required">Asset <span style="color:red;font-weight:700">*</span></label>
    <select class="form-select" name="asset_id" id="asset_id" required>
        <?php foreach ($assets as $ast): ?>
            <option value="<?= $ast['id'] ?>"
                <?= isset($request['asset_id']) && $request['asset_id'] == $ast['id'] ? 'selected' : '' ?>>
                <?= esc($ast['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group mb-3">
    <label class="required">Requested Quantity <span style="color:red;font-weight:700">*</span></label>
    <input class="form-control" type="number" min="1" name="requested_quantity" id="requested_quantity"
        value="<?= esc($request['requested_quantity'] ?? 1) ?>" required>
</div>

