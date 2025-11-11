<!-- Hidden Fields -->
<input type="hidden" id="userId" name="userId" value="<?= esc($user['id'] ?? '') ?>">
<input type="hidden" id="encryptedDesignationId" name="encryptedDesignationId" value="<?= esc($user['encrypted_designation_id'] ?? ($user['designation_id'] ?? '')) ?>">
<input type="hidden" id="encryptedDepartmentId" name="encryptedDepartmentId" value="<?= esc($user['encrypted_department_id'] ?? ($user['department_id'] ?? '')) ?>">


<div class="form-group">
    <label class="required">Name<span style="color:red;font-weight:700;">*</span></label>
    <input 
        class="form-control"
        type="text"
        name="name"
        id="nameInput"
        placeholder="Enter Name"
        value="<?= esc($user['name'] ?? $user['Name'] ?? '') ?>">
</div>

<!-- Designation & Department -->
<div class="row">
    <!-- Designation -->
    <div class="col-sm-6 form-group">
        <label>Designation<span style="color:red;font-weight:700;">*</span></label>
        <select class="form-select" name="designation_id" id="designationSelect" required>
            <option value="">Select Designation</option>
            <?php if (!empty($designations)): ?>
                <?php foreach ($designations as $designation): ?>
                    <option 
                        value="<?= esc($designation['id']) ?>"
                        <?= (!empty($user['designation_id']) && (string)$user['designation_id'] === (string)$designation['id']) ? 'selected' : '' ?>>
                        <?= esc($designation['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <!-- Department -->
    <div class="col-sm-6 form-group">
        <label>Department<span style="color:red;font-weight:700;">*</span></label>
        <select class="form-select" name="department_id" id="departmentSelect" required>
            <option value="">Select Department</option>
            <?php if (!empty($departments)): ?>
                <?php foreach ($departments as $department): ?>
                    <option 
                        value="<?= esc($department['id']) ?>"
                        <?= (!empty($user['department_id']) && (string)$user['department_id'] === (string)$department['id']) ? 'selected' : '' ?>>
                        <?= esc($department['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
</div>

<!-- Email -->
<div class="form-group">
    <label>Email<span style="color:red;font-weight:700;">*</span></label>
    <input 
        class="form-control"
        type="email"
        name="email"
        id="emailInput"
        placeholder="Enter Email"
        value="<?= esc($user['email'] ?? '') ?>">
</div>

<!-- Mobile -->
<div class="form-group">
    <label for="phone">Mobile<span style="color:red;font-weight:700;">*</span></label>
    <input 
        id="phone" 
        type="tel" 
        name="phone_number" 
        class="form-control"
        placeholder="Enter phone number"
        value="<?= esc($user['phone_number'] ?? '') ?>">

    <input 
        type="hidden" 
        name="country_code" 
        id="country_code"
        value="<?= esc($user['country_code'] ?? '') ?>">
</div>

<!-- Password -->
<div class="form-group">
    <label>Password<span style="color:red;font-weight:700;">*</span></label>
    <input 
        class="form-control"
        type="password"
        name="password"
        id="passwordInput"
        placeholder="Leave blank to keep current password">
</div>
