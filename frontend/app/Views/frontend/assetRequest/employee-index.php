<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Asset Request List</h1>

    <button class="btn btn-primary my-2 font-bold" 
        data-bs-toggle="offcanvas"
        data-bs-target="#addRequestCanvas"
        title="Add Request">
        <i class="fa fa-plus mx-2"></i> Add Request
    </button>
</div>


<!-- ---------------- FILTER SECTION ---------------- -->
<div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded m-3">
    <h5 class="pb-2 mb-2">Filters</h5>

    <div class="col-md-4">
        <input type="text" id="filterEmployee" class="form-control" placeholder="Search Employee">
    </div>

    <div class="col-md-4">
        <input type="text" id="filterAsset" class="form-control" placeholder="Search Asset">
    </div>

    <div class="col-md-2">
        <select id="filterStatus" class="form-control">
            <option value="">-- Status --</option>
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="denied">Denied</option>
            <option value="onhold">On Hold</option>
        </select>
    </div>

    <div class="col-md-1">
        <button class="btn btn-primary btn-block font-bold" id="applyFilter">
            <i class="fa fa-search"></i> Search
        </button>
    </div>

    <div class="col-md-1">
        <button id="resetFilter" class="btn btn-secondary btn-block font-bold">Reset</button>
    </div>
</div>



<!-- ---------------- TABLE ---------------- -->
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered" id="request-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Asset</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $row): ?>
                            <tr>
                                <td><?= esc($row['employee_name']) ?></td>
                                <td><?= esc($row['asset_name']) ?></td>
                                <td><?= esc($row['requested_quantity']) ?></td>
                                <td>
                                    <?php
                                        $status = strtolower($row['request_status']);
                                        $badgeClass = 'badge bg-secondary';

                                        switch ($status) {
                                            case 'pending':
                                                $badgeClass = 'badge bg-secondary text-light';
                                                break;
                                            case 'onhold':
                                                $badgeClass = 'badge bg-warning text-dark';
                                                break;
                                            case 'accepted':
                                                $badgeClass = 'badge bg-success';
                                                break;
                                            case 'denied':
                                                $badgeClass = 'badge bg-danger';
                                                break;
                                        }
                                    ?>

                                    <span class="<?= $badgeClass ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>

                                <td><?= esc(date('Y-m-d', strtotime($row['request_date']))) ?></td>
                                <td><?= esc($row['remarks']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary"
                                        onclick="editRecord('<?= base_url('request-edit') ?>', '<?= $row['id'] ?>')">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <!-- <button class="btn btn-sm btn-danger deleteBtn"
                                        data-id="<?= $row['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button> -->

                                   
                                        <!-- <button class="btn btn-sm btn-primary changeStatusBtn"
                                            data-id="<?= $row['id'] ?>">
                                            Change Status
                                        </button> -->
                                   
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Update Request Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="update_request_id">

        <div class="form-group">
          <label>Status</label>
          <select id="update_status" class="form-control">
          <option value="">--Select Status--</option>
            <option value="accepted">Accept</option>
            <option value="onhold">Hold</option>
            <option value="denied">Deny</option>
          </select>
        </div>
        <div class="form-group">
          <label>Remarks</label>
          <textarea row="4" column="10" class="form-control" name="remark"></textarea>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" id="saveStatusBtn">Save</button>
      </div>

    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addRequestCanvas" aria-labelledby="addRequestCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addRequestCanvasLabel">Create Asset Request</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <form id="requestForm">

      <!-- Asset -->
      <div class="mb-3">
        <label for="asset_id" class="form-label">Asset</label>
        <select id="asset_id" name="asset_id" class="form-select" required>
          <option value="">Select Asset</option>
          <!-- Load dynamically -->
        </select>
      </div>

      <!-- Quantity -->
      <div class="mb-3">
        <label for="quantity" class="form-label">Request Quantity</label>
        <input type="number" id="quantity" name="quantity" min="1" class="form-control" placeholder="Enter quantity" required>
      </div>

      <!-- Submit button -->
      <div class="text-end">
        <button type="submit" id="requestSubmit" class="btn btn-primary">Submit</button>
      </div>

    </form>
  </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="editDepartmentCanvas" aria-labelledby="editDepartmentCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="editDepartmentCanvasLabel">Update Asset Request</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="requestForm">
       
    <div id="editFormData"></div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit"  id="requestForm">Submit</button>
        </div>
    </form>
  </div>
</div>


<!-- ---------------- JS SECTION ---------------- -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function () {

// When offcanvas opens
$('#addRequestCanvas').on('shown.bs.offcanvas', function () {
    loadAssets();
});
function loadAssets() {
    $.ajax({
        url: "<?= base_url('asset') ?>",
        method: "GET",
        success: function (res) {
            console.log(res);
            if (res.success) {
                let html = '<option value="">--Select Asset--</option>';
                res.data.forEach(d => {
                    html += `<option value="${d.id}">${d.name}</option>`;
                });
                $("#asset_id").html(html);
            }
           
        }
    });
}

// Submit Request Form (Create/Update)
$(document).on('submit', '#requestForm', function (e) {
    e.preventDefault();

    let id = $('#req_id').val();
    let url = id 
        ? '<?= base_url("request-update") ?>/' + id 
        : '<?= base_url("request-store") ?>';

    $.ajax({
        url: url,
        type: "POST",
        data: $(this).serialize(),
        success: function (response) {

            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message || 'Request saved successfully!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.error || 'Something went wrong!'
            });
        }
    });

});


});

$(document).ready(function() {

    var table = $('#request-table').DataTable({
        pageLength: 10,
        ordering: false
    });

    // Filters
    $('#applyFilter').on('click', function() {
        table.column(0).search($('#filterEmployee').val());
        table.column(1).search($('#filterAsset').val());
        table.column(3).search($('#filterStatus').val());
        table.draw();
    });

    $('#resetFilter').on('click', function() {
        $('#filterEmployee').val('');
        $('#filterAsset').val('');
        $('#filterStatus').val('');

        table.columns().search('');
        table.draw();
    });

   

    // Delete
    $('.deleteBtn').click(function() {
        let id = $(this).data('id');

        Swal.fire({
        title: 'Are you sure?',
        text: "This Asset Request will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('request-delete') ?>/" + id,
                    type: "DELETE",
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

$(document).on('click', '.changeStatusBtn', function () {
    let id = $(this).data('id');
    let currentStatus = $(this).data('current-status');

    $("#update_request_id").val(id);
    $("#update_status").val(currentStatus);

    let modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
});

$("#saveStatusBtn").click(function () {
    let id = $("#update_request_id").val();
    let status = $("#update_status").val();

    if (!status) {
        Swal.fire({
            icon: 'warning',
            title: 'Select Status',
            text: 'Please choose a status before saving.'
        });
        return;
    }

    $.ajax({
        url: "<?= base_url('assetrequest-status') ?>",
        type: "POST",
        data: { id, status },
        success: function (res) {
            console.log(res);

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated Successfully',
                    text: res.message || 'Status updated successfully',
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: res.message || 'Something went wrong'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Unable to update status'
            });
        }
    });
});



</script>

<?= $this->endSection() ?>
