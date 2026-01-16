<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Admin List</h1>
    <button class="btn btn-primary my-2 font-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#addUserCanvas" aria-controls="addUserCanvas" title="Add">
        <i class="fa fa-plus mx-2"></i> Add User
    </button>
</div>

<div class="page-content fade-in-up">

    <!-- ===== FILTERS (mirrors asset-index.php) ===== -->
    <div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
        <h5 class="pb-2 mb-2">Filters</h5>
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control"
                       value="<?= esc($name ?? '') ?>"
                       placeholder="Search by Name">
            </div>

            <div class="col-md-3">
                <input type="text" name="email" class="form-control"
                       value="<?= esc($email ?? '') ?>"
                       placeholder="Search by Email">
            </div>

            <div class="col-md-2">
                <select id="filterDepartment" name="department_id" class="form-control">
                    <option value="">-- Select Department --</option>
                </select>
            </div>

            <div class="col-md-2">
                <select id="filterDesignation" name="designation_id" class="form-control">
                    <option value="">-- Select Designation --</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" id="applyFilter" class="btn btn-primary w-100 font-bold"
                   title="Search"> <i class="fa fa-search mx-2"></i>Search</button>
                <a href="<?= base_url('users-list') ?>" class="btn btn-secondary w-100 font-bold">Reset</a>
            </div>
        </div>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="users-table" cellspacing="0" width="100%">
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
                                <td><?= esc($user['name'] ?? '') ?></td>
                                <td><?= esc($user['email'] ?? '') ?></td>
                                <td>
                                    <?php if (!empty($user['country_code'])): ?>
                                        +<?= esc($user['country_code']) ?>
                                    <?php endif; ?>
                                    <?= esc($user['phone_number'] ?? '') ?>
                                </td>
                                <td><?= esc($user['department_name'] ?? '') ?></td>
                                <td><?= esc($user['designation_name'] ?? '') ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn tooltip-btn"
                                        onclick="edit('<?= base_url('user-edit') ?>', '<?= $user['id'] ?>')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn tooltip-btn" data-id="<?= esc($user['id']) ?>">
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

            <?php
            // ===== PAGINATION (exactly like asset-index.php) =====
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = max(1, min($currentPage, $totalPages));

            $lastPage = $totalPages;
            $currentCount = count($users);

            $startPage = max(1, $currentPage - 1);
            $endPage   = min($lastPage, $currentPage + 1);

            $start = (($currentPage - 1) * $limit) + 1;
            $end   = $start + $currentCount - 1;
            ?>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing <?= $start ?>–<?= $end ?>
                </div>

                <nav>
                    <ul class="pagination mb-0">
                        <!-- First -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" 
                               href="<?= $currentPage > 1 ? base_url('users-list?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('users-list?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('users-list?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('users-list?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('users-list?page=' . $lastPage) : '#' ?>">
                                Last
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>

<!-- Edit Offcanvas -->
<div class="offcanvas offcanvas-end" 
     tabindex="-1"
     id="editUserCanvas"
     aria-labelledby="editUserCanvasLabel"
     data-bs-backdrop="true"
     data-bs-scroll="false">
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

<!-- Add Offcanvas -->
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
            <small id="confirmError" class="text-danger font-bold"></small>
        </div>
        <div class="form-group mt-3">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>

// ========== EDIT FUNCTION (GLOBAL - mirrors asset-index.php) ==========
function edit(requestUrl, id) {
    $.ajax({
        url: requestUrl,
        method: "POST",
        data: { id: id },
        beforeSend: function () {
            $("#editFormData").html('<div class="text-center p-3">Loading...</div>');
        },
        success: function (response) {
            $("#editFormData").html(response);
            let el = document.getElementById("editUserCanvas");
            let canvas = new bootstrap.Offcanvas(el);
            canvas.show();
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.error || 'Failed to load user'
            });
        }
    });
}

$(document).ready(function() {

// ========== PHONE INPUT SETUP ==========
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

// ========== LOAD DESIGNATIONS & DEPARTMENTS ==========
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
        }
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
        }
    }
});

// ========== FORM SUBMISSION ==========
$(document).on('submit', '#createUserForm', function(e){
    e.preventDefault();
   
    const $form = $(this);
    const $phoneInput = $form.find('#phone');
    const $countryCodeInput = $form.find('#country_code');
    
    // Handle phone
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
        }
    } catch (err) {
        console.warn('Phone normalization failed', err);
    }

    const userId = $form.find('#userId').val();
    const requestUrl = userId ? '<?= base_url("user-update") ?>/' + userId : '<?= base_url("user-store") ?>';

    $.ajax({
        url: requestUrl,
        method: 'POST',
        data: $form.serialize(),
        success: function(response){
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || (userId ? 'Updated!' : 'Added!'),
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    const editCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('editUserCanvas'));
                    const addCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addUserCanvas'));
                    
                    if (editCanvas) editCanvas.hide();
                    if (addCanvas) addCanvas.hide();
                    
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
                text: xhr.responseJSON?.error || 'Something went wrong!'
            });
        }
    });
});

// ========== DELETE USER ==========
$('.deleteBtn').on('click', function() {
    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('user-delete') ?>/' + id,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || 'Deleted successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.error || 'Failed to delete'
                    });
                }
            });
        }
    });
});

$('[data-bs-target="#addUserCanvas"]').on('click', function() {
    $('#createUserForm')[0].reset();
    $('#userId').val('');
});

});

// ========== PAGINATION & FILTER LOGIC (mirrors asset-index.php exactly) ==========
$(document).ready(function () {

let userTable;

initDataTable();  
loadDepartments();
loadDesignations();

function initDataTable() {
    if ($.fn.DataTable.isDataTable('#users-table')) {
        $('#users-table').DataTable().clear().destroy();
    }

    userTable = $('#users-table').DataTable({
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
        department_id: $("#filterDepartment").val() || urlParams.get('department_id') || '',
        designation_id: $("#filterDesignation").val() || urlParams.get('designation_id') || ''
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
                $link.attr('href', `<?= base_url('users-list') ?>?page=${page}${queryString}`);
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
    window.location.href = `<?= base_url('users-list') ?>?${queryString}`;
});

function loadDepartments() {
    $.ajax({
        url: "<?= base_url('departments') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedDept = urlParams.get('department_id') || '';
                
                let html = '<option value="">-- Select Department --</option>';
                res.data.forEach(d => {
                    const selected = d.id == selectedDept ? 'selected' : '';
                    html += `<option value="${d.id}" ${selected}>${d.name}</option>`;
                });
                $("#filterDepartment").html(html);
            }
        }
    });
}

function loadDesignations() {
    $.ajax({
        url: "<?= base_url('designations') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedDesig = urlParams.get('designation_id') || '';
                
                let html = '<option value="">-- Select Designation --</option>';
                res.data.forEach(d => {
                    const selected = d.id == selectedDesig ? 'selected' : '';
                    html += `<option value="${d.id}" ${selected}>${d.name}</option>`;
                });
                $("#filterDesignation").html(html);
            }
        }
    });
}

// Populate form fields from URL on page load
function populateFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    $("input[name=name]").val(urlParams.get('name') || '');
    $("input[name=email]").val(urlParams.get('email') || '');
}

// Initialize filters from URL
populateFiltersFromURL();

// Update pagination links if filters are active
if (hasActiveFilters()) {
    updatePaginationLinks();
}

});

</script>

<?= $this->endSection() ?>