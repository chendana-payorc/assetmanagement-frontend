<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Asset Assignment List</h1>
    <button class="btn btn-primary my-2 font-bold" type="button" data-bs-toggle="offcanvas" data-bs-target="#addAssignmentCanvas" aria-controls="addAssignmentCanvas" title="Assign">
        <i class="fa fa-plus mx-2"></i> Assign Asset
    </button>
</div>

<div class="page-content fade-in-up">

    <!-- ================= FILTER SECTION ================= -->
    <div class="card mb-4 shadow-sm p-3 bg-light rounded">
        <form method="GET" action="<?= base_url('assetassignment-list') ?>">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Asset Name</label>
                    <input type="text" name="asset_name" class="form-control" value="<?= esc($asset_name ?? '') ?>" placeholder="Search Asset Name">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="<?= esc($model ?? '') ?>" placeholder="Search Model">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Employee Name</label>
                    <input type="text" name="employee_name" class="form-control" value="<?= esc($employee_name ?? '') ?>" placeholder="Search Employee">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Assigned Quantity</label>
                    <input type="text" name="assigned_quantity" class="form-control" value="<?= esc($assigned_quantity ?? '') ?>" placeholder="Search Quantity">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Assigned Date</label>
                    <input type="date" name="assigned_date" class="form-control" value="<?= esc($assigned_date ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Select Status --</option>
                        <option value="assigned" <?= (isset($status) && $status=='assigned') ? 'selected' : '' ?>>Assigned</option>
                        <option value="returned" <?= (isset($status) && $status=='returned') ? 'selected' : '' ?>>Returned</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 font-bold" id="applyFilter" title="Search">
                        <i class="fa fa-search mx-2"></i>Search
                    </button>
                    <a href="<?= base_url('assetassignment-list') ?>" class="btn btn-secondary font-bold w-100">Reset</a>
                </div>

            </div>
        </form>
    </div>
    <!-- =============== END FILTER SECTION ================== -->

    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="assignment-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Model</th>
                        <th>Employee Name</th>
                        <th>Assigned Quantity</th>
                        <th>Assigned Date</th>
                        <th>Handover Person</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Asset Name</th>
                        <th>Model</th>
                        <th>Employee Name</th>
                        <th>Assigned Quantity</th>
                        <th>Assigned Date</th>
                        <th>Handover Person</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($assignments)): ?>
                        <?php foreach ($assignments as $a): ?>
                            <tr>
                                <td><?= esc($a['asset_name']) ?></td>
                                <td><?= esc($a['model']) ?></td>
                                <td><?= esc($a['employee_name']) ?></td>
                                <td><?= esc($a['assigned_quantity']) ?></td>
                                <td><?= esc($a['assigned_date']) ?></td>
                                <td><?= esc($a['handover_person'] ?? '-') ?></td>
                                <td><?= esc($a['status']) === 'assigned' ? 'Assigned' : 'Returned' ?></td>
                                <td>
                                    <?php if (isset($a['status']) && $a['status'] === 'assigned'): ?>
                                        <button class="btn btn-sm btn-primary editBtn" title="Edit" data-id="<?= esc($a['id']) ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-danger deleteBtn" title="Delete" data-id="<?= esc($a['id']) ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <?php if (isset($a['status']) && $a['status'] === 'assigned'): ?>
                                        <button class="btn btn-sm btn-warning returnBtn" title="Return" data-id="<?= esc($a['id']) ?>">
                                            <i class="fa fa-undo"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No assignments found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ================= OFFCANVAS ASSIGN ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addAssignmentCanvas" aria-labelledby="addAssignmentCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="addAssignmentCanvasLabel">Assign Asset</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="assignmentForm">
            <input type="hidden" name="assign_id" id="assign_id">

            <div class="form-group mb-3">
                <label class="required">Asset <span style="color:red">*</span></label>
                <select name="asset_id" id="assetSelect" class="form-select" required>
                    <option value="">-- Select Asset --</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label>Model</label>
                <input class="form-control" type="text" id="assetModel" readonly>
            </div>

            <div class="form-group mb-3">
                <label class="required">Employee <span style="color:red">*</span></label>
                <select name="employee_id" id="employeeSelect" class="form-select" required>
                    <option value="">-- Select Employee --</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="required">Assigned Quantity <span style="color:red">*</span></label>
                <input class="form-control" type="number" min="1" name="assigned_quantity" required>
            </div>

            <div class="form-group mb-3">
                <label>Handover Person</label>
                <input class="form-control" type="text" name="handover_person" placeholder="Enter Handover Person">
            </div>

            <div class="form-group">
                <button class="btn btn-primary" id="assignmentSubmit" type="submit">Assign</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= OFFCANVAS EDIT ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editAssignmentCanvas" aria-labelledby="editAssignmentCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="editAssignmentCanvasLabel">Edit Assignment</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="assignmentEditForm">
            <div id="editFormData"></div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(function () {

    // Populate asset & employee selects
    $('[data-bs-target="#addAssignmentCanvas"]').on('click', function () {
        $('#assignmentForm')[0].reset();
        $('#assetModel').val('');
        loadAssets();
        loadEmployees();
    });

    function loadAssets() {
        $.get('<?= base_url('asset-list-json') ?>', function(res){
            if (res && Array.isArray(res)) {
                let html = '<option value="">-- Select Asset --</option>';
                res.forEach(a => {
                    html += `<option value="${a.id}" data-model="${a.model ?? ''}">${a.name} ${a.model ? ' - ' + a.model : ''}</option>`;
                });
                $('#assetSelect').html(html);
            } else {
                $('#assetSelect').html('<option value="">No assets</option>');
            }
        });
    }

    function loadEmployees() {
        $.get('<?= base_url('employee-list-json') ?>', function(res){
            if (res && Array.isArray(res)) {
                let html = '<option value="">-- Select Employee --</option>';
                res.forEach(e => {
                    html += `<option value="${e.id}">${e.name}</option>`;
                });
                $('#employeeSelect').html(html);
            } else {
                $('#employeeSelect').html('<option value="">No employees</option>');
            }
        });
    }

    $(document).on('change', '#assetSelect', function(){
        let model = $(this).find('option:selected').data('model') || '';
        $('#assetModel').val(model);
    });

    // Assign form
    $(document).on('submit', '#assignmentForm', function(e){
        e.preventDefault();
        $.post('<?= base_url('assetassignment-store') ?>', $(this).serialize(), function(response){
            if(response.success){
                Swal.fire({icon:'success', title:'Assigned!', text: response.message, showConfirmButton:false, timer:1200})
                    .then(()=> location.reload());
            } else {
                Swal.fire({icon:'error', title:'Error!', text: response.message});
            }
        }, 'json');
    });

    // Return button
    $(document).on('click', '.returnBtn', function(){
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will mark the asset as returned.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, return it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('assetassignment-return') ?>/' + id,
                    method: 'PUT',
                    success: function(response) {
                        Swal.fire({icon:'success', title:'Returned!', showConfirmButton:false, timer:1200})
                        .then(()=> location.reload());
                    }
                });
            }
        });
    });

    // Edit button (UPDATED)
    $(document).on('click', '.editBtn', function(){
        let id = $(this).data('id');
        $.get('<?= base_url('assetassignment-edit') ?>/' + id, function(html){
            $('#editFormData').html(html);
            let offcanvas = new bootstrap.Offcanvas(document.getElementById('editAssignmentCanvas'));
            offcanvas.show();
        });
    });

    // Update form
    $(document).on('submit', '#assignmentEditForm', function(e){
        e.preventDefault();
        let id = $(this).find('input[name="assign_id"]').val();
        $.post('<?= base_url('assetassignment-update') ?>/' + id, $(this).serialize(), function(response){
            if(response.success){
                Swal.fire({icon:'success', title:'Updated!', showConfirmButton:false, timer:1200})
                .then(()=> location.reload());
            } else {
                Swal.fire({icon:'error', title:'Error!', text: response.message});
            }
        }, 'json');
    });

    // Delete button
    $(document).on('click', '.deleteBtn', function(){
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This assignment will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: '<?= base_url('assetassignment-delete') ?>/' + id,
                    method: 'DELETE',
                    success:function(response){
                        Swal.fire({icon:'success', title:'Deleted!', showConfirmButton:false, timer:1200})
                        .then(()=> location.reload());
                    }
                });
            }
        });
    });

    function initDataTable(){
        let table = $('#assignment-table');
        if (!table.length) return;
        if ($.fn.DataTable.isDataTable(table)) table.DataTable().destroy();
        table.DataTable({pageLength:10, ordering:false, responsive:true});
    }
    initDataTable();

});
</script>

<?= $this->endSection() ?>
