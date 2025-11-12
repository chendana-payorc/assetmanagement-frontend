<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Department List</h1>
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addDepartmentCanvas" aria-controls="addDepartmentCanvas">
        Add Department +
    </button>
</div>

<div class="page-content fade-in-up">
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
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $dept): ?>
                            <tr>
                                <td><?= esc($dept['name']) ?></td>
                                <td><?= $dept['status'] == 1 ? 'Active' : 'Inactive' ?></td>
                                <td>
                                <button class="btn btn-sm btn-primary editBtn"
    onclick="editRecord('<?= base_url('department-edit') ?>', '<?= esc($dept['id']) ?>')">
    <i class="fa fa-edit"></i>
</button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $dept['id'] ?>">
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
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addDepartmentCanvas" aria-labelledby="addDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addDepartmentCanvasLabel">Create Department</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="departmentForm">
        <input type="hidden" name="id" id="dept_id">
        <div class="form-group mb-3">
            <label class="required">Name</label>
            <input class="form-control" type="text" name="name" id="dept_name" placeholder="Enter Name" required>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(document).on('submit', '#departmentForm', function(e) {
    e.preventDefault();
    const $form = $(this);
    const id = $form.find('#dept_id').val();
    let url = id ? '<?= base_url('department-update') ?>/' + id : '<?= base_url('department-store') ?>';
    
    $.ajax({
        url: url,
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Saved successfully!',
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
    $('#dept_status').val('1'); // default active
    $('#dept_status').closest('.form-group').hide(); // hide status dropdown on create

    $('#addDepartmentCanvasLabel').text('Create Department');
    $('#submitBtn').text('Submit');
});


});
</script>

<?= $this->endSection() ?>
