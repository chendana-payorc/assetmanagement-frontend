<!-- Hidden Fields -->
<input type="hidden" id="userId" name="userId" value="<?= esc($user['id'] ?? '') ?>">
<input type="hidden" id="encryptedDesignationId" name="encryptedDesignationId" value="<?= esc($user['encrypted_designation_id'] ?? ($user['designation_id'] ?? '')) ?>">
<input type="hidden" id="encryptedDepartmentId" name="encryptedDepartmentId" value="<?= esc($user['encrypted_department_id'] ?? ($user['department_id'] ?? '')) ?>">

<!-- <?php print_r($user)?> -->

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
      
<!-- Designation -->
<select name="designation_id" id="designationSelect" class="form-select" required>
    <option value="">Select Designation</option>
    <?php foreach ($designations as $designation): ?>
        <option value="<?= esc($designation['id']) ?>" 
            <?= (!empty($user['encrypted_designation_id']) && $user['encrypted_designation_id'] === $designation['id']) ? 'selected' : '' ?>>
            <?= esc($designation['name']) ?>
        </option>
    <?php endforeach; ?>
</select>



    </div>

    <!-- Department -->
    <div class="col-sm-6 form-group">
        <label>Department<span style="color:red;font-weight:700;">*</span></label>
       <!-- Department -->
<select name="department_id" id="departmentSelect" class="form-select" required>
    <option value="">Select Department</option>
    <?php foreach ($departments as $department): ?>
        <option value="<?= esc($department['id']) ?>" 
            <?= (!empty($user['encrypted_department_id']) && $user['encrypted_department_id'] === $department['id']) ? 'selected' : '' ?>>
            <?= esc($department['name']) ?>
        </option>
    <?php endforeach; ?>
</select>
    </div>
</div>

<div class="form-group">
    <label>Email <span style="color:red;font-weight:700;">*</span></label>
    <input 
        class="form-control"
        type="email"
        name="email"
        id="emailInput"
        placeholder="Enter Email"
        value="<?= esc($user['email'] ?? '') ?>">
</div>

<div class="form-group">
    <label for="phone">Mobile <span style="color:red;font-weight:700;">*</span></label>
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
    <label>Password</label>
    <input 
        class="form-control"
        type="password"
        name="password"
        id="passwordInput"
        placeholder="Leave blank to keep current password">
</div>
<small id="passwordRules" class="text-muted">
                <ul style="padding-left:15px; margin-top:5px;">
                    <li id="ruleUpper">One uppercase letter (A-Z)</li>
                    <li id="ruleLower">One lowercase letter (a-z)</li>
                    <li id="ruleNumber">One number (0-9)</li>
                    <li id="ruleSpecial">One special character (!@#$%^&*)</li>
                    <li id="ruleLength">Minimum 8 characters</li>
                </ul>
            </small>

            <div class="form-group">
                <label>Confirm Password</label>
                <input class="form-control" type="password" name="confirm_password" id="confirmPasswordInput" placeholder="Leave blank to keep current password">
                <small id="confirmError" class="text-danger font-bold"></small>
            </div>
