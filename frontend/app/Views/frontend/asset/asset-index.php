<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Assets List</h1>
    <button class="btn btn-primary my-2 font-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#addDepartmentCanvas" aria-controls="addDepartmentCanvas" title="Add">
    <i class="fa fa-plus mx-2"></i> 
        Add Asset
    </button>
</div>
 
<div class="page-content fade-in-up">

    <div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
      <h5 class="pb-2 mb-2">Filters</h5>
        <div class="row g-3 align-items-end">
        <div class="col-md-3">
    <input type="text" name="asset_id" class="form-control"
           value="<?= esc($asset_id ?? '') ?>"
           placeholder="Search Asset ID">
</div>

                <div class="col-md-3">
                   
                    <input type="text" name="model" class="form-control" 
                           value="<?= esc($model ?? '') ?>" 
                           placeholder="Search Model">
                </div>

                <div class="col-md-3">
                   
                    <input type="text" name="name" class="form-control" 
                           value="<?= esc($name ?? '') ?>" 
                           placeholder="Search Name">
                </div>

                <div class="col-md-2">
                    
                    <input type="number" name="count" class="form-control"
                           value="<?= esc($count ?? '') ?>" 
                           placeholder="Enter Count">
                </div>
                <div class="col-md-2">
                    
                    <input type="text" name="price" class="form-control"
                           value="<?= esc($count ?? '') ?>" 
                           placeholder="Enter price">
                </div>
                <div class="col-md-2">
                <select id="selectCategory" class="form-control">
                    <option value="">-- Select AssetCategory --</option>
                </select>
                </div>

                <div class="col-md-2">
                    <select id="selectSupplier" class="form-control">
                        <option value="">-- Select AssetSupplier --</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" id="applyFilter" class="btn btn-primary w-100 font-bold"
                       title="Search"> <i class="fa fa-search mx-2"></i>Search</button>
                    <a href="<?= base_url('asset-list') ?>" class="btn btn-secondary w-100 font-bold">Reset</a>
                </div>

            </div>
      
    </div>
   
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="asset-table" cellspacing="0" width="100%">
                <thead>
                <tr>    
                        <th>Asset ID</th>
                        <th>Model</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Assigned Assets</th>
                        <th>Remaining Assets</th>
                        <th>Price</th>
                        <th>Asset Category</th>
                        <th>Asset Supplier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Asset ID</th>
                        <th>Model</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Assigned Assets</th>
                        <th>Remaining Assets</th>
                        <th>Price</th>
                        <th>Asset Category</th>
                        <th>Asset Supplier</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($assets)): ?>
                        <?php foreach ($assets as $dept): ?>
                            <tr>
                                <td><?= esc($dept['asset_id']) ?></td>
                                <td><?= esc($dept['model']) ?></td>
                                <td><?= esc($dept['name']) ?></td>
                                <td><?= esc($dept['count']) ?></td>
                                <td><?= esc($dept['assigned_assets']) ?></td>
                                <td><?= esc($dept['remaining_assets']) ?></td>
                                <td><?= esc($dept['price']) ?></td>
                                <td><?= esc($dept['category_name']) ?></td>
                                <td><?= esc($dept['supplier_name']) ?></td>
                                <td>
                                <button class="btn btn-sm btn-primary editBtn tooltip-btn"
                                    onclick="edit('<?= base_url('asset-edit') ?>', '<?= esc($dept['id']) ?>')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                    <button class="btn btn-sm btn-danger deleteBtn tooltip-btn" data-id="<?= $dept['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No assets found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php
$limit = 5;

$currentPage = (int) ($_GET['page'] ?? 1);
$currentPage = $currentPage > 0 ? $currentPage : 1;

$limit = 5;

$currentPage = (int) ($_GET['page'] ?? 1);
$currentPage = max(1, min($currentPage, $totalPages));

$lastPage = $totalPages; //  REAL last page from backend

$currentCount = count($assets);

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
                   href="<?= $currentPage > 1 ? base_url('asset-list?page=1') : '#' ?>">
                    First
                </a>
            </li>

            <!-- Prev -->
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="<?= $currentPage > 1 ? base_url('asset-list?page=' . ($currentPage - 1)) : '#' ?>">
                    Prev
                </a>
            </li>

            <!-- Page numbers -->
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                    <a class="page-link"
                       href="<?= $i === $currentPage ? '#' : base_url('asset-list?page=' . $i) ?>">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <!-- Next -->
            <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="<?= $currentPage < $lastPage ? base_url('asset-list?page=' . ($currentPage + 1)) : '#' ?>">
                    Next
                </a>
            </li>

            <!-- Last -->
            <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                <a class="page-link"
                   href="<?= $currentPage < $lastPage ? base_url('asset-list?page=' . $lastPage) : '#' ?>">
                    Last
                </a>
            </li>

        </ul>
    </nav>
</div>





        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" 
     tabindex="-1"
     id="editAssetCanvas"
     aria-labelledby="editAssetCanvasLabel"
     data-bs-backdrop="true"
     data-bs-scroll="false">

  <div class="offcanvas-header">
    <h5 id="editAssetCanvasLabel">Edit Asset</h5>
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
 
<div class="offcanvas offcanvas-end" tabindex="-1" id="addDepartmentCanvas" aria-labelledby="addDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addDepartmentCanvasLabel">Create Asset</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="departmentForm">
        <input type="hidden" name="id" id="dept_id">
        <div class="form-group mb-3">
        <label class="required">Asset ID <span style="color:red;font-weight:700;">*</span></label>
        <input class="form-control" type="text" name="asset_id" id="dept_assetid"
               placeholder="Enter Asset ID" required>
    </div>
        <div class="form-group mb-3">
            <label class="required">Model<span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control" type="text" name="model" id="dept_model" placeholder="Enter Model" required>
        </div>
        <div class="form-group mb-3">
            <label class="required">Name<span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control" type="text" name="name" id="dept_name" placeholder="Enter Name" required>
        </div>
        <div class="row">
        <div class="col-sm-6 form-group">
            <label class="required">Count<span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control" type="number" name="count" id="dept_count" placeholder="Enter Count" required>
        </div>
        <div class="col-sm-6 form-group">
            <label class="required">Price<span style="color:red;font-weight:700;">*</span></label>
            <input class="form-control"
       type="number"
       name="price"
       id="dept_price"
       placeholder="Enter Price"
       step="0.01"
       min="0"
       required>

        </div>
        </div>
        <div class="row">
        <div class="col-sm-6 form-group">
        <label class="required">Asset Category<span style="color:red;font-weight:700;">*</span></label>
            <select id="fetchCategory" class="form-control" name="category_id">
                    <option value="">-- Select AssetCategory --</option>
            </select>
        </div>
        <div class="col-sm-6 form-group">
        <label class="required">Asset Supplier<span style="color:red;font-weight:700;">*</span></label>
                <select id="fetchSupplier" class="form-control" name="supplier_id">
                        <option value="">-- Select AssetSupplier --</option>
                </select>
        </div>
        </div>
        <div class="form-group mb-3">
            <label class="required">Description</label>
            <textarea class="form-control" name="description" id="dept_des" placeholder="Enter Description"></textarea>
        </div>
     
        <div class="form-group">
            <button class="btn btn-primary" type="submit" id="submitBtn">Submit</button>
        </div>
    </form>
  </div>
</div>

 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

window.edit = function (requestUrl, id) {
    $.ajax({
        url: requestUrl,
        method: "POST",
        data: { id: id },
        beforeSend: function () {
            $("#editFormData").html('<div class="text-center p-3">Loading...</div>');
        },
        success: function (response) {
            console.log(response); 
            $("#editFormData").html(response);

            let el = document.getElementById("editAssetCanvas");

            let canvas = new bootstrap.Offcanvas(el);
            canvas.show();
        }
    });
};

});

$(document).ready(function() {
   

$.ajax({
    url: '<?= base_url('/category') ?>',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success && response.data) {
            $('#fetchCategory').empty().append('<option value="">--Select Category--</option>');
            $.each(response.data, function(index, item) {
                $('#fetchCategory').append('<option value="' + item.id + '">' + item.name + '</option>');
            });
        }
    },
    error: function() {
        console.error('Failed to load category');
    }
});

$.ajax({
    url: '<?= base_url('/supplier') ?>',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.success && response.data) {
            $('#fetchSupplier').empty().append('<option value="">--Select Supplier--</option>');
            $.each(response.data, function(index, item) {
                $('#fetchSupplier').append('<option value="' + item.id + '">' + item.supplier_name + '</option>');
            });
        }
    },
    error: function() {
        console.error('Failed to load supplier');
    }
});

$(document).on('submit', '#departmentForm', function(e) {
    e.preventDefault();
    const $form = $(this);
    const id = $form.find('#dept_id').val();
   
 
    let url = id
        ? '<?= base_url('asset-update') ?>/' + id
        : '<?= base_url('asset-store') ?>';
 
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
                    const editCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('editAssetCanvas'));
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
 
$('.deleteBtn').on('click', function() {
    let id = $(this).data('id');
    console.log(id);
 
    Swal.fire({
        title: 'Are you sure?',
        text: "This Asset will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('asset-delete') ?>/' + id,
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

});

 
// Replace the existing JavaScript in asset-index.php with this:

$(document).ready(function () {

let assetTable;

initDataTable();  
loadCategories();
loadSuppliers();

function initDataTable() {
    if ($.fn.DataTable.isDataTable('#asset-table')) {
        $('#asset-table').DataTable().clear().destroy();
    }

    assetTable = $('#asset-table').DataTable({
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
        asset_id: $("input[name=asset_id]").val() || urlParams.get('asset_id') || '',
        model: $("input[name=model]").val() || urlParams.get('model') || '',
        name: $("input[name=name]").val() || urlParams.get('name') || '',
        count: $("input[name=count]").val() || urlParams.get('count') || '',
        price: $("input[name=price]").val() || urlParams.get('price') || '',
        category_id: $("#selectCategory").val() || urlParams.get('category_id') || '',
        supplier_id: $("#selectSupplier").val() || urlParams.get('supplier_id') || ''
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
                $link.attr('href', `<?= base_url('asset-list') ?>?page=${page}${queryString}`);
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
    window.location.href = `<?= base_url('asset-list') ?>?${queryString}`;
});

$("#resetFilter").on("click", function () {
    // Clear form inputs
    $("input[name=asset_id]").val('');
    $("input[name=model]").val('');
    $("input[name=name]").val('');
    $("input[name=count]").val('');
    $("input[name=price]").val('');
    $("#selectCategory").val('');
    $("#selectSupplier").val('');
    
    // Redirect to page 1 without filters
    window.location.href = '<?= base_url('asset-list') ?>?page=1';
});

function loadCategories() {
    $.ajax({
        url: "<?= base_url('category') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedCategory = urlParams.get('category_id') || '';
                
                let html = '<option value="">-- Select Asset Category --</option>';
                res.data.forEach(c => {
                    const selected = c.id == selectedCategory ? 'selected' : '';
                    html += `<option value="${c.id}" ${selected}>${c.name}</option>`;
                });
                $("#selectCategory").html(html);
            }
        }
    });
}

function loadSuppliers() {
    $.ajax({
        url: "<?= base_url('supplier') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                const urlParams = new URLSearchParams(window.location.search);
                const selectedSupplier = urlParams.get('supplier_id') || '';
                
                let html = '<option value="">-- Select Asset Supplier --</option>';
                res.data.forEach(s => {
                    const selected = s.id == selectedSupplier ? 'selected' : '';
                    html += `<option value="${s.id}" ${selected}>${s.supplier_name}</option>`;
                });
                $("#selectSupplier").html(html);
            }
        }
    });
}

// Populate form fields from URL on page load
function populateFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    $("input[name=asset_id]").val(urlParams.get('asset_id') || '');
    $("input[name=model]").val(urlParams.get('model') || '');
    $("input[name=name]").val(urlParams.get('name') || '');
    $("input[name=count]").val(urlParams.get('count') || '');
    $("input[name=price]").val(urlParams.get('price') || '');
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