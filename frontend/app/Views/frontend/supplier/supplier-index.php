<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Asset Supplier List</h1>
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addSupplierCanvas" aria-controls="addSupplierCanvas">
        Add Supplier +
    </button>
</div>

<div class="page-content fade-in-up">

    <!-- ================= FILTER SECTION ================= -->
    <div class="card mb-4 shadow-sm p-3 bg-light rounded">
        <form method="GET" action="<?= base_url('supplier-list') ?>">
            <div class="row g-3 align-items-end">

            <div class="col-md-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="<?= esc($name ?? '') ?>" placeholder="Search Name">
</div>

<div class="col-md-3">
    <label class="form-label">Email</label>
    <input type="text" name="email" class="form-control" value="<?= esc($email ?? '') ?>" placeholder="Search Email">
</div>

<div class="col-md-3">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" value="<?= esc($phone ?? '') ?>" placeholder="Search Phone">
</div>

<div class="col-md-3">
    <label class="form-label">Organization</label>
    <input type="text" name="organization_name" class="form-control" value="<?= esc($organization_name ?? '') ?>" placeholder="Search Organization">
</div>

<div class="col-md-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="<?= esc($address ?? '') ?>" placeholder="Search Address">
            </div>

<div class="col-md-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="">-- Select Status --</option>
        <option value="active" <?= (isset($status) && $status=='active') ? 'selected' : '' ?>>Active</option>
        <option value="inactive" <?= (isset($status) && $status=='inactive') ? 'selected' : '' ?>>Inactive</option>
    </select>
</div>


                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="<?= base_url('supplier-list') ?>" class="btn btn-outline-secondary w-100">Reset</a>
                </div>

            </div>
        </form>
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
                                <td><?= esc($sup['status']) == 'active' ? 'Active' : 'Inactive' ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn" data-id="<?= $sup['id'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $sup['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No suppliers found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

            <!-- default status hidden to avoid duplicate dropdown on create (backend expects status) -->
            <div class="form-group mb-3">
    <label class="required">Status <span style="color:red">*</span></label>
    <select name="status" class="form-select" required>
        <option value="active" selected>Active</option>
        <option value="inactive">Inactive</option>
    </select>
</div>


            <div class="form-group">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= OFFCANVAS EDIT ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editSupplierCanvas" aria-labelledby="editSupplierCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="editSupplierCanvasLabel">Edit Supplier</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="supplierEditForm">
            <div id="editFormData"></div>
            <!-- Buttons live inside edit-form partial to avoid duplicates -->
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $('#supplier-table').DataTable({
        pageLength: 10,
        ordering: false,
    });

    // CREATE
    $(document).on('submit', '#supplierForm', function(e) {
        e.preventDefault();
        const $form = $(this);
        $.ajax({
            url: '<?= base_url('supplier-store') ?>',
            method: 'POST',
            data: $form.serialize(),
            success: function(response) {
                if (response.success || response.status === 'success') {
                    Swal.fire({icon:'success', title:'Success!', text: response.message || 'Supplier created', showConfirmButton:false, timer:1400})
                    .then(()=> location.reload());
                } else {
                    Swal.fire({icon:'error', title:'Error!', text: response.message || 'Something went wrong'});
                }
            },
            error: function(xhr) {
                Swal.fire({icon:'error', title:'Error!', text: xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong'});
            }
        });
    });

    // DELETE
    $(document).on('click', '.deleteBtn', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This supplier will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('supplier-delete') ?>/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        Swal.fire({icon:'success', title:'Deleted!', text: response.message || 'Deleted', showConfirmButton:false, timer:1200})
                        .then(()=> location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire({icon:'error', title:'Error!', text: xhr.responseJSON?.error || 'Failed to delete'});
                    }
                });
            }
        });
    });

    // OPEN ADD (reset)
    $('[data-bs-target="#addSupplierCanvas"]').on('click', function() {
        $('#sup_id').val('');
        $('#supplierForm')[0].reset();
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
            Swal.fire({icon:'success', title:'Updated!', text: response.message || 'Supplier updated', showConfirmButton:false, timer:1200})
            .then(()=> location.reload());
        } else {
            Swal.fire({icon:'error', title:'Error!', text: response.message || 'Update failed'});
        }
    }, 'json').fail(function(xhr){
        Swal.fire({icon:'error', title:'Error!', text: xhr.responseJSON?.error || 'Update failed'});
    });
});
</script>

<?= $this->endSection() ?>
