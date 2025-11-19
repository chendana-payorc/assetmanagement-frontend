<input type="hidden" name="id" id="org_id" value="<?= esc($organization['id'] ?? '') ?>">

<div class="form-group mb-3">
    <label class="required">Organization Name <span style="color:red">*</span></label>
    <input type="text" name="name" class="form-control"
           value="<?= esc($organization['name'] ?? '') ?>" required>
</div>

<div class="form-group mb-3">
    <label class="required">Email <span style="color:red">*</span></label>
    <input type="email" name="email" class="form-control"
           value="<?= esc($organization['email'] ?? '') ?>" required>
</div>

<div class="form-group mb-3">
    <label class="required">Contact Number <span style="color:red">*</span></label>
    <input type="text" name="contact_no" class="form-control"
           value="<?= esc($organization['contact_no'] ?? '') ?>" required>
</div>

<div class="form-group mb-3">
    <label class="required">Address <span style="color:red">*</span></label>
    <textarea name="address" class="form-control" rows="2" required><?= esc($organization['address'] ?? '') ?></textarea>
</div>

<div class="form-group mb-3">
    <label class="required">Country <span style="color:red">*</span></label>
    <input type="text" name="country" class="form-control"
           value="<?= esc($organization['country'] ?? '') ?>" required>
</div>

<div class="form-group mb-3">
    <label class="required">State <span style="color:red">*</span></label>
    <input type="text" name="state" class="form-control"
           value="<?= esc($organization['state'] ?? '') ?>" required>
</div>

<div class="form-group mb-3">
    <label class="required">City <span style="color:red">*</span></label>
    <input type="text" name="city" class="form-control"
           value="<?= esc($organization['city'] ?? '') ?>" required>
</div>

<div class="form-group mb-3">
    <label class="required">Zipcode <span style="color:red">*</span></label>
    <input type="text" name="zipcode" class="form-control"
           value="<?= esc($organization['zipcode'] ?? '') ?>" required>
</div>

<button class="btn btn-primary" type="submit">Save</button>
<a href="<?= base_url('organization-list') ?>" class="btn btn-secondary">Cancel</a>
