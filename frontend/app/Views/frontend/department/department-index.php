<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Department List</h1>
    <button class="btn btn-primary my-2 font-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#addDepartmentCanvas" aria-controls="addDepartmentCanvas" title="Add">
        <i class="fa fa-plus mx-2"></i> Add Department
    </button>
</div>

<div class="page-content fade-in-up">

    <!-- ===== FILTERS (mirrors asset-index.php) ===== -->
    <div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
        <h5 class="pb-2 mb-2">Filters</h5>
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <input type="text" name="name" class="form-control" 
                       value="<?= esc($name ?? '') ?>" 
                       placeholder="Search Name">
            </div>

            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="1" <?= ($status ?? '') == '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= ($status ?? '') == '0' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" id="applyFilter" class="btn btn-primary w-100 font-bold"
                   title="Search"> <i class="fa fa-search mx-2"></i>Search</button>
                <a href="<?= base_url('department-list') ?>" class="btn btn-secondary w-100 font-bold">Reset</a>
            </div>
        </div>
    </div>

    <!-- ===== TABLE ===== -->
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="department-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $dept): ?>
                            <tr>
                                <td><?= esc($dept['name']) ?></td>
                                <td>
                                    <?php if ($dept['status'] == 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn tooltip-btn"
                                        onclick="editRecord('<?= base_url('department-edit') ?>', '<?= esc($dept['id']) ?>')">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn tooltip-btn" data-id="<?= $dept['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No departments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php
            // ===== PAGINATION (exactly like asset-index.php) =====
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = max(1, min($currentPage, $totalPages));

            $lastPage = $totalPages;
            $currentCount = count($departments);

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
                               href="<?= $currentPage > 1 ? base_url('department-list?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('department-list?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('department-list?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('department-list?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('department-list?page=' . $lastPage) : '#' ?>">
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
     id="editDepartmentCanvas"
     aria-labelledby="editDepartmentCanvasLabel"
     data-bs-backdrop="true"
     data-bs-scroll="false">
  <div class="offcanvas-header">
    <h5 id="editDepartmentCanvasLabel">Edit Department</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="departmentForm">
        <div id="editFormData"></div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>

<!-- Add Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addDepartmentCanvas" aria-labelledby="addDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addDepartmentCanvasLabel">Create Department</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="departmentForm">
        <input type="hidden" name="id" id="dept_id">
        <div class="form-group mb-3">
            <label class="required">Name<span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control" type="text" name="name" id="dept_name" placeholder="Enter Name" required>
        </div>
        <!-- Status field - HIDDEN by default for create -->
        <div class="form-group mb-3" id="statusFieldGroup" style="display: none;">
            <label>Status</label>
            <select class="form-select" name="status" id="dept_status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

// ========== EDIT FUNCTION (GLOBAL - mirrors asset-index.php) ==========
function editRecord(requestUrl, id) {
    $.ajax({
        url: requestUrl,
        method: "POST",
        data: { id: id },
        beforeSend: function () {
            $("#editFormData").html('<div class="text-center p-3">Loading...</div>');
        },
        success: function (response) {
            $("#editFormData").html(response);
            let el = document.getElementById("editDepartmentCanvas");
            let canvas = new bootstrap.Offcanvas(el);
            canvas.show();
        }
    });
}

$(document).ready(function() {

    // ========== FORM SUBMISSION ==========
    $(document).on('submit', '#departmentForm', function(e) {
        e.preventDefault();
        const $form = $(this);
        const id = $form.find('#dept_id').val();
        
        let url = id
            ? '<?= base_url('department-update') ?>/' + id
            : '<?= base_url('department-store') ?>';
        
        $.ajax({
            url: url,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || (id ? 'Updated successfully!' : 'Added successfully!'),
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        const editCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('editDepartmentCanvas'));
                        const addCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addDepartmentCanvas'));
                        
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
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.error || 'Something went wrong!'
                });
            }
        });
    });

    // ========== DELETE DEPARTMENT ==========
    $('.deleteBtn').on('click', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This department will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('department-delete') ?>/' + id,
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

    $('[data-bs-target="#addDepartmentCanvas"]').on('click', function() {
        $('#dept_id').val('');
        $('#dept_name').val('');
        $('#dept_status').val('1');
        $('#statusFieldGroup').hide(); // Hide status field for create
        $('#addDepartmentCanvasLabel').text('Create Department');
        $('#submitBtn').text('Submit');
    });

});

// ========== PAGINATION & FILTER LOGIC (mirrors asset-index.php exactly) ==========
$(document).ready(function () {

    let departmentTable;

    initDataTable();

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#department-table')) {
            $('#department-table').DataTable().clear().destroy();
        }

        departmentTable = $('#department-table').DataTable({
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
            status: $("select[name=status]").val() || urlParams.get('status') || ''
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
                    $link.attr('href', `<?= base_url('department-list') ?>?page=${page}${queryString}`);
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
        window.location.href = `<?= base_url('department-list') ?>?${queryString}`;
    });

    // Populate form fields from URL on page load
    function populateFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        $("input[name=name]").val(urlParams.get('name') || '');
        $("select[name=status]").val(urlParams.get('status') || '');
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