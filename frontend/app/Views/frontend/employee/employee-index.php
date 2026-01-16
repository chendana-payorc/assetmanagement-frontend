<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Employee List</h1>
  
    <button class="btn btn-primary my-2 font-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#addUserCanvas" aria-controls="addUserCanvas" title="Add">
        <i class="fa fa-plus mx-2"></i> Add Employee
    </button>
</div>

<div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
    <h5 class="pb-2 mb-2">Filters</h5>
    <div class="row g-3 align-items-end">
        <div class="col-md-2">
            <input type="text" name="name" class="form-control" 
                   value="<?= esc($name ?? '') ?>" 
                   placeholder="Search by Name">
        </div>

        <div class="col-md-2">
            <input type="text" name="email" class="form-control" 
                   value="<?= esc($email ?? '') ?>" 
                   placeholder="Search by Email">
        </div>

        <div class="col-md-2">
            <select name="department_id" class="form-control" id="filterDepartment">
                <option value="">All Departments</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="designation_id" class="form-control" id="filterDesignation">
                <option value="">All Designations</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="button" id="applyFilter" class="btn btn-primary btn-block w-100 font-bold" title="Search">
                <i class="fa fa-search mx-2"></i>Search
            </button>
        </div>

        <div class="col-md-2">
            <a href="<?= base_url('employee-list') ?>" class="btn btn-secondary btn-block w-100 font-bold" title="Reset">Reset</a>
        </div>
    </div>
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
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $user): ?>
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
                                    <button class="btn btn-sm btn-primary editBtn tooltip-btn" 
                                        title="Edit"
                                        data-id="<?= esc($user['id'] ?? '') ?>" 
                                        onclick="edit('<?= base_url('employee-edit') ?>', '<?= ($user['id'] ?? '') ?>')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn tooltip-btn" 
                                        data-id="<?= esc($user['id'] ?? '') ?>"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No employees found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = $currentPage > 0 ? $currentPage : 1;
            $currentPage = max(1, min($currentPage, $totalPages));
            $lastPage = $totalPages;
            $currentCount = count($employees);

            // Range for page numbers
            $startPage = max(1, $currentPage - 1);
            $endPage   = min($lastPage, $currentPage + 1);

            // Showing text
            $start = (($currentPage - 1) * $limit) + 1;
            $end   = $start + $currentCount - 1;
            ?>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <!-- Showing text -->
                <div class="text-muted">
                    Showing <?= $start ?>–<?= $end ?>
                </div>

                <nav>
                    <ul class="pagination mb-0">
                        <!-- First -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" 
                               href="<?= $currentPage > 1 ? base_url('employee-list?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('employee-list?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('employee-list?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('employee-list?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('employee-list?page=' . $lastPage) : '#' ?>">
                                Last
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addUserCanvas" aria-labelledby="addUserCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addUserCanvasLabel">Create Employee</h5>
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
            <label>Confirm Password <span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control" type="password" name="confirm_password" id="confirmPasswordInput" placeholder="Confirm Password">
            <small id="confirmError" class="text-danger"></small>
        </div>

        <div class="form-group mt-3">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>

<!-- Edit Employee Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editUserCanvas" aria-labelledby="editUserCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="editUserCanvasLabel">Update Employee</h5>
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

let employeeTable;

initDataTable();

function initDataTable() {
    if ($.fn.DataTable.isDataTable('#example-table')) {
        $('#example-table').DataTable().clear().destroy();
    }

    employeeTable = $('#example-table').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info: false
    });
}

// Get current filter values from URL or form
function getFilterParams() {
    const urlParams = new URLSearchParams(window.location.search);
    return {
        name: $("input[name=name]").val() || urlParams.get('name') || '',
        email: $("input[name=email]").val() || urlParams.get('email') || '',
        department_id: $("select[name=department_id]").val() || urlParams.get('department_id') || '',
        designation_id: $("select[name=designation_id]").val() || urlParams.get('designation_id') || ''
    };
}

// Check if any filters are active
function hasActiveFilters() {
    const filters = getFilterParams();
    return Object.values(filters).some(val => val !== '');
}

// Update pagination links with filter parameters
function updatePaginationLinks() {
    const filters = getFilterParams();
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = parseInt(urlParams.get('page')) || 1;
    
    // Build query string
    let queryString = '';
    Object.keys(filters).forEach(key => {
        if (filters[key]) {
            queryString += `&${key}=${encodeURIComponent(filters[key])}`;
        }
    });
    
    // Update all pagination links
    $('.pagination .page-link').each(function() {
        const $link = $(this);
        const href = $link.attr('href');
        
        if (href && href !== '#') {
            const url = new URL(href, window.location.origin);
            const page = url.searchParams.get('page');
            
            if (page) {
                $link.attr('href', `<?= base_url('employee-list') ?>?page=${page}${queryString}`);
            }
        }
    });
}

$("#applyFilter").on("click", function () {
    // Reset to page 1 when applying new filters
    const filters = getFilterParams();
    let queryString = 'page=1';
    
    Object.keys(filters).forEach(key => {
        if (filters[key]) {
            queryString += `&${key}=${encodeURIComponent(filters[key])}`;
        }
    });
    
    // Redirect with filters in URL
    window.location.href = `<?= base_url('employee-list') ?>?${queryString}`;
});

// Populate form fields from URL on page load
function populateFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    $("input[name=name]").val(urlParams.get('name') || '');
    $("input[name=email]").val(urlParams.get('email') || '');
    
    const deptId = urlParams.get('department_id');
    const desigId = urlParams.get('designation_id');
    
    if (deptId) {
        setTimeout(() => $("select[name=department_id]").val(deptId), 100);
    }
    if (desigId) {
        setTimeout(() => $("select[name=designation_id]").val(desigId), 100);
    }
}

// Load departments for filter
function loadDepartmentsForFilter() {
    $.ajax({
        url: "<?= base_url('departments') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                let html = '<option value="">All Departments</option>';
                res.data.forEach(d => {
                    html += `<option value="${d.id}">${d.name}</option>`;
                });
                $("#filterDepartment").html(html);
                
                // Set value from URL after options loaded
                const urlParams = new URLSearchParams(window.location.search);
                const deptId = urlParams.get('department_id');
                if (deptId) {
                    $("#filterDepartment").val(deptId);
                }
            }
        }
    });
}

// Load designations for filter
function loadDesignationsForFilter() {
    $.ajax({
        url: "<?= base_url('designations') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                let html = '<option value="">All Designations</option>';
                res.data.forEach(d => {
                    html += `<option value="${d.id}">${d.name}</option>`;
                });
                $("#filterDesignation").html(html);
                
                // Set value from URL after options loaded
                const urlParams = new URLSearchParams(window.location.search);
                const desigId = urlParams.get('designation_id');
                if (desigId) {
                    $("#filterDesignation").val(desigId);
                }
            }
        }
    });
}

// Initialize filters from URL
populateFiltersFromURL();
loadDepartmentsForFilter();
loadDesignationsForFilter();

// Update pagination links if filters are active
if (hasActiveFilters()) {
    updatePaginationLinks();
}

// Rest of your existing JavaScript code continues here...
// (Phone input, form submission, edit, delete functionality)
// Add this JavaScript section after the pagination logic in employee-index.php

// Phone input initialization
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
            initialCountry: 'in',
            preferredCountries: ['in', 'us', 'gb', 'ae'],
            autoPlaceholder: 'polite',
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js'
        });
    }
}).catch(() => {
    console.error('Failed to load phone input library');
});

const storeUrl = '<?= base_url('employee-store') ?>';
const updateUrl = '<?= base_url('employee-update') ?>';

const resetFormState = () => {
    $('#createUserForm')[0].reset();
    $('#userId').val('');
    $('#country_code').val('');
    $('#designationSelect').val('');
    $('#departmentSelect').val('');
    $('#submitBtn').text('Submit');
    $('#addUserCanvasLabel').text('Create Employee');
    $('#passwordInput').attr('placeholder', 'Enter Password');
    if (itiInstance) {
        itiInstance.setCountry('in');
        itiInstance.setNumber('');
    } else {
        $('#phone').val('');
    }
};

const applySelectValue = (selector, value) => {
    if (!value) return;
    const $select = $(selector);
    if ($select.find('option[value="' + value + '"]').length) {
        $select.val(value);
    } else if (selector === '#designationSelect') {
        pendingDesignationId = value;
    } else if (selector === '#departmentSelect') {
        pendingDepartmentId = value;
    }
};

// Load designations for form
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

// Load departments for form
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

// Form submission
$(document).on('submit', '#createUserForm', function(e){
    e.preventDefault();
   
    const $form = $(this);
    const $phoneInput = $form.find('#phone');
    const $countryCodeInput = $form.find('#country_code');
    
    // Handle phone input
    try {
        let iti = null;
        const isEditForm = $form.closest('#editUserCanvas').length > 0;
        
        if (isEditForm && window.editFormItiInstance) {
            iti = window.editFormItiInstance;
        } else if (!isEditForm && itiInstance) {
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
            const phoneVal = ($phoneInput.val() || '').replace(/\D/g, '');
            if (phoneVal.length > 10) {
                $phoneInput.val(phoneVal.slice(-10));
            }
        }
    } catch (err) {
        console.warn('Phone normalization failed', err);
    }

    // Validation
    const nameVal = ($form.find('#nameInput').val() || '').trim();
    const emailVal = ($form.find('#emailInput').val() || '').trim();
    const phoneVal = ($phoneInput.val() || '').trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (nameVal.length < 3 || nameVal.length > 50) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: 'Name must be between 3 and 50 characters.'
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
                const successMessage = response.message || (userId ? 'Employee updated successfully!' : 'Employee added successfully!');
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
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

// Delete functionality
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
                url: '<?= base_url('employee-delete') ?>/' + id,
                method: 'DELETE',
                success: function(response) {
                    if (response && response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Employee deleted successfully',
                            showConfirmButton: false,
                            timer: 1200
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response?.message || 'Failed to delete user.'
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

window.editFormItiInstance = null;

}); // End of document.ready

// Edit function (global scope)
function edit(url, id) {
    $.ajax({
        url: url,
        method: 'POST',
        data: { id: id },
        success: function(response) {
            if (typeof response === 'object' && response !== null && !(response instanceof jQuery)) {
                if (response.error || response.message || response.success === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.error || response.message || 'Something went wrong!'
                    });
                    return;
                }
            }
           
            if (typeof response === 'string') {
                const trimmed = response.trim();
                if (trimmed.startsWith('{') && trimmed.endsWith('}')) {
                    try {
                        const errorData = JSON.parse(response);
                        if (errorData.error || errorData.message || errorData.success === false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorData.error || errorData.message || 'Something went wrong!'
                            });
                            return;
                        }
                    } catch (e) {}
                }
            }
          
            $('#editFormData').html(response);
            initPasswordValidation();
            
            window.editFormItiInstance = null;
            
            function initPhoneInput() {
                const phoneInput = document.querySelector('#editFormData #phone');
                if (!phoneInput) return;
                
                if (typeof window.intlTelInput === 'undefined') {
                    setTimeout(initPhoneInput, 100);
                    return;
                }
                
                const countryCode = $('#editFormData #country_code').val() || '';
                const phoneNumber = $('#editFormData #phone').val() || '';
              
                try {
                    const iti = window.intlTelInput(phoneInput, {
                        separateDialCode: true,
                        initialCountry: 'in',
                        preferredCountries: ['in', 'us', 'gb', 'ae'],
                        autoPlaceholder: 'polite',
                        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js'
                    });
                    
                    window.editFormItiInstance = iti;
                    
                    if (countryCode && phoneNumber) {
                        try {
                            if (window.intlTelInputGlobals && window.intlTelInputGlobals.getCountryData) {
                                const countryData = window.intlTelInputGlobals.getCountryData().find(function(item) {
                                    return item.dialCode === countryCode;
                                });
                                if (countryData) {
                                    iti.setCountry(countryData.iso2);
                                }
                            }
                            const dialPrefixedNumber = `+${countryCode}${phoneNumber}`;
                            iti.setNumber(dialPrefixedNumber);
                        } catch (err) {
                            console.warn('Failed to set phone number', err);
                            phoneInput.value = phoneNumber;
                        }
                    } else if (phoneNumber) {
                        phoneInput.value = phoneNumber;
                    }
                } catch (err) {
                    console.warn('Failed to initialize phone input', err);
                }
            }
            
            setTimeout(initPhoneInput, 100);
           
            $('#editUserCanvas #submitBtn').text('Update');
            $('#editUserCanvasLabel').text('Update Employee');
           
            const canvasEl = document.getElementById('editUserCanvas');
            const offcanvasInstance = bootstrap.Offcanvas.getOrCreateInstance(canvasEl);
            offcanvasInstance.show();
            
            canvasEl.addEventListener('hidden.bs.offcanvas', function() {
                window.editFormItiInstance = null;
            }, { once: true });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong!'
            });
        }
    });
}

</script>

<?= $this->endSection() ?>