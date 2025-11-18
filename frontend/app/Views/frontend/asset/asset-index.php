<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
 
<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Assets List</h1>
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addDepartmentCanvas" aria-controls="addDepartmentCanvas">
        Add Asset +
    </button>
</div>
 
<div class="page-content fade-in-up">

Filter

<!-- ================= FILTER SECTION ================= -->
<div class="card mb-4 shadow-sm p-3 bg-light rounded">
        <form method="GET" action="<?= base_url('asset-list') ?>">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" 
                           value="<?= esc($model ?? '') ?>" 
                           placeholder="Search Model">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" 
                           value="<?= esc($name ?? '') ?>" 
                           placeholder="Search Name">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Count</label>
                    <input type="number" name="count" class="form-control"
                           value="<?= esc($count ?? '') ?>" 
                           placeholder="Enter Count">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="<?= base_url('asset-list') ?>" class="btn btn-outline-secondary w-100">Reset</a>
                </div>

            </div>
        </form>
    </div>
    <!-- =============== END FILTER SECTION ================== -->

    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                <thead>
                <tr>
                        <th>Model</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Model</th>
                        <th>Name</th>
                        <th>Count</th>
                        <th>Description</th>
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
                                <td><?= esc($dept['description']) ?></td>
                                <td>
                                <button class="btn btn-sm btn-primary editBtn"
    onclick="editRecord('<?= base_url('asset-edit') ?>', '<?= esc($dept['id']) ?>')">
    <i class="fa fa-edit"></i>
</button>
 
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $dept['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No assets found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
            <label class="required">Model</label>
            <input class="form-control" type="text" name="model" id="dept_model" placeholder="Enter Model" required>
        </div>
        <div class="form-group mb-3">
            <label class="required">Name</label>
            <input class="form-control" type="text" name="name" id="dept_name" placeholder="Enter Name" required>
        </div>
        <div class="form-group mb-3">
            <label class="required">Count</label>
            <input class="form-control" type="number" name="count" id="dept_count" placeholder="Enter Count" required>
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
 
 
<div class="offcanvas offcanvas-end" tabindex="-1" id="editDepartmentCanvas" aria-labelledby="editDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="editDepartmentCanvasLabel">Edit Asset</h5>
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
 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
 
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
                    // ✅ Detect and close whichever offcanvas is open
                    const editCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('editDepartmentCanvas'));
                    const addCanvas = bootstrap.Offcanvas.getInstance(document.getElementById('addDepartmentCanvas'));
 
                    if (editCanvas) editCanvas.hide();
                    if (addCanvas) addCanvas.hide();
 
                    // Reload table or page
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
 
$('[data-bs-target="#addDepartmentCanvas"]').on('click', function() {
    $('#dept_id').val('');
    $('#dept_model').val('');
    $('#dept_name').val('');
    $('#dept_count').val('');
    $('#dept_des').val('');
   
    $('#addDepartmentCanvasLabel').text('Create Asset');
    $('#submitBtn').text('Submit');
});
 
 
 
});
 
 
</script>
 
<?= $this->endSection() ?>