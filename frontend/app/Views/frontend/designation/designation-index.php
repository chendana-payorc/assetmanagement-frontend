<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Designation List</h1>
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addDepartmentCanvas" aria-controls="addDepartmentCanvas">
        Add Designation +
    </button>
</div>

<div class="page-content fade-in-up">

<div class="page-content fade-in-up">

Fiilter
    <!-- ================= FILTER SECTION ================= -->
    <div class="card mb-4 shadow-sm p-3 bg-light rounded">
        <form method="GET" action="<?= base_url('designation-list') ?>">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control"
                           value="<?= esc($name ?? '') ?>"
                           placeholder="Search Name">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Select Status --</option>
                        <option value="active" <?= (isset($status) && $status=='active')?'selected':'' ?>>Active</option>
                        <option value="inactive" <?= (isset($status) && $status=='inactive')?'selected':'' ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="<?= base_url('designation-list') ?>" class="btn btn-outline-secondary w-100">Reset</a>
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
                    <?php if (!empty($designations)): ?>
                        <?php foreach ($designations as $dept): ?>
                            <tr>
                                <td><?= esc($dept['name']) ?></td>
                                <td><?= $dept['status'] == 1 ? 'Active' : 'Inactive' ?></td>
                                <td>
                                <button class="btn btn-sm btn-primary editBtn"
    onclick="editRecord('<?= base_url('designation-edit') ?>', '<?= esc($dept['id']) ?>')">
    <i class="fa fa-edit"></i>
</button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $dept['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No designations found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addDepartmentCanvas" aria-labelledby="addDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addDepartmentCanvasLabel">Create Designation</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="departmentForm">
        <input type="hidden" name="id" id="dept_id">
        <div class="form-group mb-3">
            <label class="required">Name<span style="color:red;font-weight:700">*</span></label>
            <input class="form-control" type="text" name="name" id="dept_name" placeholder="Enter Name">
        </div>
        <div class="form-group mb-3">
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


<div class="offcanvas offcanvas-end" tabindex="-1" id="editDepartmentCanvas" aria-labelledby="editDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="editDepartmentCanvasLabel">Edit Designation</h5>
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
   
    let url = id ? '<?= base_url('designation-update') ?>/' + id : '<?= base_url('designation-store') ?>';
    //console.log(url);
    $.ajax({
    url: url,
    method: 'POST',
    data: $(this).serialize(),
    success: function(response) {
        // Handle success and failed responses properly
        if (response.status === 'success' || response.success === true) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message || 'Designation created successfully!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Close offcanvas and reload page
                let offcanvas = bootstrap.Offcanvas.getInstance($('#addDepartmentCanvas'));
                if (offcanvas) offcanvas.hide();
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


// 🔴 Delete Department
$('.deleteBtn').on('click', function() {
    let id = $(this).data('id');
   //console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "This designation will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('designation-delete') ?>/' + id,
                method: 'DELETE',
                success: function(response) {
                    console.log(response);
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
    $('#dept_status').val('1'); // default active
    $('#dept_status').closest('.form-group').hide(); // hide status dropdown on create

    $('#addDepartmentCanvasLabel').text('Create Designation');
    $('#submitBtn').text('Submit');
});


});



</script>

<?= $this->endSection() ?>
