<div>
    <input type="hidden" name="assign_id" value="<?= esc($assignment['id']) ?>">

    <div class="form-group mb-3">
        <label class="required">Asset <span style="color:red">*</span></label>
        <select name="asset_id" id="editAssetSelect" class="form-select" required>
            <option value="">-- Select Asset --</option>
            <?php foreach($assets as $asset): ?>
                <option value="<?= esc($asset['id']) ?>" 
                        data-model="<?= esc($asset['model'] ?? '') ?>" 
                        <?= ($assignment['asset_id'] == $asset['id']) ? 'selected' : '' ?>>
                    <?= esc($asset['name']) ?><?= !empty($asset['model']) ? ' - ' . esc($asset['model']) : '' ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label>Model</label>
        <?php 
            $selectedAsset = array_filter($assets, fn($a) => $a['id'] == $assignment['asset_id']);
            $modelValue = $selectedAsset ? current($selectedAsset)['model'] : '';
        ?>
        <input class="form-control" type="text" id="editAssetModel" value="<?= esc($modelValue) ?>" readonly>
    </div>

    <div class="form-group mb-3">
        <label class="required">Employee <span style="color:red">*</span></label>
        <select name="employee_id" id="editEmployeeSelect" class="form-select" required>
            <option value="">-- Select Employee --</option>
            <?php foreach($employees as $emp): ?>
                <option value="<?= esc($emp['id']) ?>" <?= ($assignment['employee_id'] == $emp['id']) ? 'selected' : '' ?>>
                    <?= esc($emp['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label class="required">Assigned Quantity <span style="color:red">*</span></label>
        <input class="form-control" type="number" min="1" name="assigned_quantity" value="<?= esc($assignment['assigned_quantity']) ?>" required>
    </div>

    <div class="form-group mb-3">
        <label>Handover Person</label>
        <input class="form-control" type="text" name="handover_person" value="<?= esc($assignment['handover_person'] ?? '') ?>" placeholder="Enter Handover Person">
    </div>

    <div class="form-group">
        <button class="btn btn-primary" type="submit">Update</button>
    </div>
</div>

<script>
    // Update Model when asset changes
    $('#editAssetSelect').on('change', function(){
        let model = $(this).find('option:selected').data('model') || '';
        $('#editAssetModel').val(model);
    });
</script>
