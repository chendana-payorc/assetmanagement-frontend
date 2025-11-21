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
                        <th>Model</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Price</th>
                        <th>Asset Category</th>
                        <th>Asset Supplier</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Model</th>
                        <th>Name</th>
                        <th>Count</th>
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
                                <td><?= esc($dept['model']) ?></td>
                                <td><?= esc($dept['name']) ?></td>
                                <td><?= esc($dept['count']) ?></td>
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
            <input class="form-control" type="number" name="price" id="dept_count" placeholder="Enter Price" required>
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
        pageLength: 10,
        ordering: false
    });
}

$("#applyFilter").on("click", function () {
    loadAssets();
});

$("#resetFilter").on("click", function () {
    $("input[name=model]").val('');
    $("input[name=name]").val('');
    $("input[name=count]").val('');
    $("input[name=price]").val('');
    $("#selectCategory").val('');
    $("#selectSupplier").val('');

    loadAssets();
});

function loadCategories() {
    $.ajax({
        url: "<?= base_url('category') ?>",
        method: "GET",
        success: function (res) {
            if (res.success) {
                let html = '<option value="">-- Select Asset Category --</option>';
                res.data.forEach(c => {
                    html += `<option value="${c.id}">${c.name}</option>`;
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
                let html = '<option value="">-- Select Asset Supplier --</option>';
                res.data.forEach(s => {
                    html += `<option value="${s.id}">${s.supplier_name}</option>`;
                });
                $("#selectSupplier").html(html);
            }
        }
    });
}

function loadAssets() {
    $.ajax({
        url: "<?= base_url('filter-assets') ?>",
        method: "GET",
        data: {
            model: $("input[name=model]").val(),
            name: $("input[name=name]").val(),
            count: $("input[name=count]").val(),
            price: $("input[name=price]").val(),
            category_id: $("#selectCategory").val(),
            supplier_id: $("#selectSupplier").val(),
        },
        success: function (res) {
          
            initDataTable();

            let rows = [];

            if (res.success && res.data.length > 0) {
                res.data.forEach(a => {
                    rows.push([
                        a.name,
                        a.model,
                        a.count,
                        a.price,
                        a.category_name,
                        a.supplier_name,
                        `
                            <button class="btn btn-sm btn-primary tooltip-btn" onclick="edit('<?= base_url('asset-edit') ?>', '${a.id}')" title="Edit">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger deleteBtn tooltip-btn" data-id="${a.id}">
                                <i class="fa fa-trash"></i>
                            </button>
                        `
                    ]);
                });
            }

            assetTable.rows.add(rows).draw();
        }
    });
}

});



</script>
 
<?= $this->endSection() ?>