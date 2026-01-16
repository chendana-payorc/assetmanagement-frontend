<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Asset Supplier List</h1>
    <button class="btn btn-primary my-2 font-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#addSupplierCanvas" aria-controls="addSupplierCanvas" title="Add">
        <i class="fa fa-plus mx-2"></i> Add Supplier
    </button>
</div>
 
<div class="page-content fade-in-up">
 
    <!-- ================= FILTER SECTION ================= -->
    <div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
        <h5 class="pb-2 mb-2">Filters</h5>
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" 
                       value="<?= esc($name ?? '') ?>" 
                       placeholder="Search Name">
            </div>

            <div class="col-md-3">
                <input type="text" name="email" class="form-control" 
                       value="<?= esc($email ?? '') ?>" 
                       placeholder="Search Email">
            </div>

            <div class="col-md-3">
                <input type="text" name="phone" class="form-control" 
                       value="<?= esc($phone ?? '') ?>" 
                       placeholder="Search Phone">
            </div>

            <div class="col-md-3">
                <input type="text" name="organization_name" class="form-control" 
                       value="<?= esc($organization_name ?? '') ?>" 
                       placeholder="Search Organization">
            </div>

            <div class="col-md-3">
                <input type="text" name="address" class="form-control" 
                       value="<?= esc($address ?? '') ?>" 
                       placeholder="Search Address">
            </div>

            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Select Status --</option>
                    <option value="active" <?= ($status ?? '') == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($status ?? '') == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="col-md-6 d-flex gap-2">
                <button type="submit" id="applyFilter" class="btn btn-primary w-100 font-bold"
                   title="Search"> <i class="fa fa-search mx-2"></i>Search</button>
                <a href="<?= base_url('supplier-list') ?>" class="btn btn-secondary w-100 font-bold">Reset</a>
            </div>
        </div>
    </div>
    <!-- =============== END FILTER SECTION ================== -->
 
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="supplier-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Organization</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Organization</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $sup): ?>
                            <tr>
                                <td><?= esc($sup['supplier_name']) ?></td>
                                <td><?= esc($sup['email']) ?></td>
                                <td><?= esc($sup['phone']) ?></td>
                                <td><?= esc($sup['organization_name']) ?></td>
                                <td><?= esc($sup['address']) ?></td>
                                <td>
                                    <?php if (esc($sup['status']) === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn tooltip-btn" title="Edit" data-id="<?= $sup['id'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn tooltip-btn" title="Delete" data-id="<?= $sup['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No suppliers found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = max(1, min($currentPage, $totalPages));

            $lastPage = $totalPages;
            $currentCount = count($suppliers);

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
                               href="<?= $currentPage > 1 ? base_url('supplier-list?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('supplier-list?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('supplier-list?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('supplier-list?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('supplier-list?page=' . $lastPage) : '#' ?>">
                                Last
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
 
</div>
 
<!-- ================= OFFCANVAS CREATE ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addSupplierCanvas" aria-labelledby="addSupplierCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="addSupplierCanvasLabel">Create Supplier</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="supplierForm">
            <input type="hidden" name="id" id="sup_id">
            <div class="form-group mb-3">
                <label class="required">Supplier Name <span style="color:red">*</span></label>
                <input class="form-control" type="text" name="supplier_name" placeholder="Enter Supplier Name" required>
            </div>

            <div class="form-group mb-3">
                <label class="required">Email <span style="color:red">*</span></label>
                <input class="form-control" type="email" name="email" placeholder="Enter Email" required>
            </div>

            <div class="form-group mb-3">
                <label class="required">Phone <span style="color:red">*</span></label>
                <input class="form-control" type="text" name="phone" placeholder="Enter Phone" required>
            </div>

            <div class="form-group mb-3">
                <label class="required">Organization Name <span style="color:red">*</span></label>
                <input class="form-control" type="text" name="organization_name" placeholder="Enter Organization Name" required>
            </div>

            <div class="form-group mb-3">
                <label class="required">Address <span style="color:red">*</span></label>
                <textarea name="address" class="form-control" rows="3" placeholder="Enter Address" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label class="required">Status <span style="color:red">*</span></label>
                <select name="status" class="form-select" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <button class="btn btn-primary" id="supplierSubmit" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
 
<!-- ================= OFFCANVAS EDIT ================= -->
<div class="offcanvas offcanvas-end" 
     tabindex="-1"
     id="editSupplierCanvas"
     aria-labelledby="editSupplierCanvasLabel"
     data-bs-backdrop="true"
     data-bs-scroll="false">
    <div class="offcanvas-header">
        <h5 id="editSupplierCanvasLabel">Edit Supplier</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="supplierEditForm">
            <div id="editFormData"></div>
        </form>
    </div>
</div>
 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(function () {
   
    $(document).on('submit', '#supplierForm', function(e) {
        e.preventDefault();
        let form = $(this);

        $.ajax({
            url: '<?= base_url('supplier-store') ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success){
                    Swal.fire({
                        icon:'success',
                        title:'Saved!',
                        text: response.message,
                        showConfirmButton:false,
                        timer:1200
                    }).then(()=> location.reload());
                } else {
                    Swal.fire({icon:'error', title:'Error!', text: response.message || 'Save failed'});
                }
            },
            error: function(xhr){
                Swal.fire({icon:'error', title:'Error!', text: xhr.responseJSON?.message || 'Something went wrong'});
            }
        });
    });

    // Reset form when opening offcanvas
    $('[data-bs-target="#addSupplierCanvas"]').on('click', function () {
        $('#supplierForm')[0].reset();
        $('#sup_id').val('');
    });

});
// EDIT -- load partial and show offcanvas
$(document).on('click', '.editBtn', function() {
    let id = $(this).data('id');
    $.post('<?= base_url('supplier-edit') ?>', {id:id}, function(html) {
        $('#editFormData').html(html);
        let offcanvas = new bootstrap.Offcanvas(document.getElementById('editSupplierCanvas'));
        offcanvas.show();
    });
});
 
// UPDATE
$(document).on('submit', '#supplierEditForm', function(e) {
    e.preventDefault();
    let form = $(this);
    let id = form.find('input[name="id"]').val();
    $.post('<?= base_url('supplier-update') ?>/' + id, form.serialize(), function(response) {
        if (response.success || response.status === 'success') {
            Swal.fire({
                icon:'success',
                title:'Updated!',
                text: response.message || 'Supplier updated',
                showConfirmButton:false,
                timer:1200
            }).then(()=> location.reload());
        } else {
            Swal.fire({icon:'error', title:'Error!', text: response.message || 'Update failed'});
        }
    }, 'json').fail(function(xhr){
        Swal.fire({icon:'error', title:'Error!', text: xhr.responseJSON?.error || 'Update failed'});
    });
});

$('.deleteBtn').on('click', function() {
    let id = $(this).data('id');
 
    Swal.fire({
        title: 'Are you sure?',
        text: "This AssetSupplier will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('supplier-delete') ?>/' + id,
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

$(document).ready(function () {

    let supplierTable;

    initDataTable();

    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#supplier-table')) {
            $('#supplier-table').DataTable().clear().destroy();
        }

        supplierTable = $('#supplier-table').DataTable({
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
            phone: $("input[name=phone]").val() || urlParams.get('phone') || '',
            organization_name: $("input[name=organization_name]").val() || urlParams.get('organization_name') || '',
            address: $("input[name=address]").val() || urlParams.get('address') || '',
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
                    $link.attr('href', `<?= base_url('supplier-list') ?>?page=${page}${queryString}`);
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
        window.location.href = `<?= base_url('supplier-list') ?>?${queryString}`;
    });

    // Populate form fields from URL on page load
    function populateFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        $("input[name=name]").val(urlParams.get('name') || '');
        $("input[name=email]").val(urlParams.get('email') || '');
        $("input[name=phone]").val(urlParams.get('phone') || '');
        $("input[name=organization_name]").val(urlParams.get('organization_name') || '');
        $("input[name=address]").val(urlParams.get('address') || '');
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