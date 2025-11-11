<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Admin List</h1>
  
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addUserCanvas" aria-controls="addUserCanvas">
        Add User+
    </button>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['name'] ?? $user['Name'] ?? '') ?></td>
                                <td><?= esc($user['email'] ?? '') ?></td>
                                <td>
                                    <?php if (!empty($user['country_code'] ?? null)): ?>
                                        +<?= esc($user['country_code']) ?>
                                    <?php endif; ?>
                                    <?= esc($user['phone_number'] ?? '') ?>
                                </td>
                                <td><?= esc($user['department_name'] ?? '') ?></td>
                                <td><?= esc($user['designation_name'] ?? '') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn" 
                                        data-id="<?= esc($user['id'] ?? '') ?>" 
                                        onclick="editRecord('<?= base_url('user-edit') ?>', '<?= ($user['id'] ?? '') ?>')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= esc($user['id'] ?? '') ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addUserCanvas" aria-labelledby="addUserCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addUserCanvasLabel">Create User</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="createUserForm">
        <input type="hidden" id="userId">
       
            <div class="form-group">
                <label class="required">Name<span style="color:red;font-weight:700;">*</span></label>
                <input class="form-control" type="text" name="name" id="nameInput" placeholder="Enter Name">
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Designation<span style="color:red;font-weight:700;">*</span></label>
                    <select class="form-select" name="designation_id" id="designationSelect" required>
                        <option value="">Select Designation</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Department<span style="color:red;font-weight:700;">*</span></label>
                    <select class="form-select" name="department_id" id="departmentSelect" required>
                        <option value="">Select Department</option>
                    </select>
                </div>
            </div>
           <div class="form-group">
                <label>Email<span style="color:red;font-weight:700;">*</span></label>
                <input class="form-control" type="email" name="email" id="emailInput" placeholder="Enter Email">
            </div>
            <div class="form-group">
              <label for="phone">Mobile<span style="color:red;font-weight:700;">*</span></label>
              <input id="phone" type="tel" name="phone_number" class="form-control" placeholder="Enter phone number">
              <input type="hidden" name="country_code" id="country_code">
            </div>

            <div class="form-group">
                <label>Password<span style="color:red;font-weight:700;">*</span></label>
                <input class="form-control" type="password" name="password" id="passwordInput" placeholder="Enter Password">
            </div>
           

        <div class="form-group mt-3">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="editUserCanvas" aria-labelledby="editUserCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="editUserCanvasLabel">Update User</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="createUserForm">        
        <div id="editFormData"></div>
        <div class="form-group mt-3">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
// Load intl-tel-input dynamically then initialize
const loadIntlTelInput = () => {
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js';
        script.onload = resolve;
        script.onerror = reject;
        document.body.appendChild(script);
    }).then(() => {
        return new Promise((resolveUtils, rejectUtils) => {
            const utils = document.createElement('script');
            utils.src = 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js';
            utils.onload = resolveUtils;
            utils.onerror = rejectUtils;
            document.body.appendChild(utils);
        });
    });
};

let itiInstance = null;
let designationOptionsLoaded = false;
let departmentOptionsLoaded = false;
let pendingDesignationId = null;
let pendingDepartmentId = null;
loadIntlTelInput().then(() => {
    const phoneInput = document.querySelector('#phone');
    if (phoneInput) {
        itiInstance = window.intlTelInput(phoneInput, {
            separateDialCode: true,
            initialCountry: 'in', // set a sensible default; user can change
            preferredCountries: ['in', 'us', 'gb', 'ae'],
            autoPlaceholder: 'polite',
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js'
        });
    }
}).catch(() => {
    console.error('Failed to load phone input library');
});

const storeUrl = '<?= base_url('user-store') ?>';
const updateUrl = '<?= base_url('user-update') ?>';

const resetFormState = () => {
    $('#createUserForm')[0].reset();
    $('#userId').val('');
    $('#country_code').val('');
    $('#designationSelect').val('');
    $('#departmentSelect').val('');
    $('#submitBtn').text('Submit');
    $('#addUserCanvasLabel').text('Create User');
    $('#passwordInput').attr('placeholder', 'Enter Password');
    if (itiInstance) {
        itiInstance.setCountry('in');
        itiInstance.setNumber('');
    } else {
        $('#phone').val('');
    }
};

const applySelectValue = (selector, value) => {
    if (!value) {
        return;
    }
    const $select = $(selector);
    if ($select.find('option[value="' + value + '"]').length) {
        $select.val(value);
    } else if (selector === '#designationSelect') {
        pendingDesignationId = value;
    } else if (selector === '#departmentSelect') {
        pendingDepartmentId = value;
    }
};

$.ajax({
    url: '<?= base_url('/designations') ?>',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success && response.data) {
            $('#designationSelect').empty().append('<option value="">Select Designation</option>');
            $.each(response.data, function(index, item) {
                $('#designationSelect').append('<option value="' + item.id + '">' + item.name + '</option>');
            });
            designationOptionsLoaded = true;
            if (pendingDesignationId) {
                $('#designationSelect').val(pendingDesignationId);
                pendingDesignationId = null;
            }
        }
    },
    error: function() {
        console.error('Failed to load designations');
    }
});

$.ajax({
    url: '<?= base_url('/departments') ?>',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success && response.data) {
            $('#departmentSelect').empty().append('<option value="">Select Department</option>');
            $.each(response.data, function(index, item) {
                $('#departmentSelect').append('<option value="' + item.id + '">' + item.name + '</option>');
            });
            departmentOptionsLoaded = true;
            if (pendingDepartmentId) {
                $('#departmentSelect').val(pendingDepartmentId);
                pendingDepartmentId = null;
            }
        }
    },
    error: function() {
        console.error('Failed to load departments');
    }
});

// Handle form submission for both add and edit forms
$(document).on('submit', '#createUserForm', function(e){
    e.preventDefault();
   
    const $form = $(this);
    const $phoneInput = $form.find('#phone');
    const $countryCodeInput = $form.find('#country_code');
    
    // Handle phone input - check if it's in edit form or add form
    try {
        let iti = null;
        const isEditForm = $form.closest('#editUserCanvas').length > 0;
        
        if (isEditForm && window.editFormItiInstance) {
            // Use the edit form's iti instance
            iti = window.editFormItiInstance;
        } else if (!isEditForm && itiInstance) {
            // Use the add form's iti instance
            iti = itiInstance;
        }
        
        if (iti) {
            const selected = iti.getSelectedCountryData();
            $countryCodeInput.val(selected && selected.dialCode ? selected.dialCode : '');
          
            if (window.intlTelInputUtils) {
                const national = iti.getNumber(window.intlTelInputUtils.numberFormat.NATIONAL) || $phoneInput.val();
                let digitsOnly = (national || '').replace(/\D/g, '');
                if (digitsOnly.length > 10) {
                    digitsOnly = digitsOnly.slice(-10);
                }
                if (digitsOnly.length === 11 && digitsOnly.startsWith('0')) {
                    digitsOnly = digitsOnly.substring(1);
                }
                $phoneInput.val(digitsOnly);
            }
        } else {
            // Fallback: use existing country code if available, or validate phone format
            const phoneVal = ($phoneInput.val() || '').replace(/\D/g, '');
            if (phoneVal.length > 10) {
                // Keep last 10 digits
                $phoneInput.val(phoneVal.slice(-10));
            }
        }
    } catch (err) {
        console.warn('Phone normalization failed', err);
    }

    // Client-side validation
    const nameVal = ($form.find('#nameInput').val() || '').trim();
    const emailVal = ($form.find('#emailInput').val() || '').trim();
    const phoneVal = ($phoneInput.val() || '').trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (nameVal.length < 5 || nameVal.length > 50) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: 'Name must be between 5 and 50 characters.'
        });
        return;
    }

    if (!emailRegex.test(emailVal)) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: 'Please enter a valid email address.'
        });
        return;
    }

    if (phoneVal.length !== 10) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: 'Phone number must be exactly 10 digits.'
        });
        return;
    }

    const userId = $form.find('#userId').val();
    const requestUrl = userId ? `${updateUrl}/${userId}` : storeUrl;

    $.ajax({
        url: requestUrl,
        method: 'POST',
        data: $form.serialize(),
        success: function(response){
            if (response.success) {
                const successMessage = response.message || (userId ? 'User updated successfully!' : 'User added successfully!');
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Close the appropriate canvas
                    const isEditForm = $form.closest('#editUserCanvas').length > 0;
                    const canvasId = isEditForm ? 'editUserCanvas' : 'addUserCanvas';
                    const canvasEl = document.getElementById(canvasId);
                    const offcanvasInstance = bootstrap.Offcanvas.getInstance(canvasEl);
                    if (offcanvasInstance) {
                        offcanvasInstance.hide();
                    } else {
                        const newInstance = new bootstrap.Offcanvas(canvasEl);
                        newInstance.hide();
                    }
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.message || 'Something went wrong!'
                });
            }
        },
        error: function(xhr){
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong!'
            });
        }
    });
});

$('[data-bs-target="#addUserCanvas"]').on('click', function() {
    resetFormState();
});

// Edit button is handled by editRecord() function in main.php
// Removed conflicting handler to avoid conflicts

$('table').on('click', '.deleteBtn', function() {
    const id = $(this).data('id');
    if (!id) return;

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('user-delete') ?>/' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'User deleted successfully',
                            showConfirmButton: false,
                            timer: 1200
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message || 'Failed to delete user.'
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.error || 'Failed to delete user.'
                    });
                }
            });
        }
    });
});

$('#addUserCanvas').on('hidden.bs.offcanvas', function() {
    resetFormState();
});


});


</script>
<?= $this->endSection() ?>

