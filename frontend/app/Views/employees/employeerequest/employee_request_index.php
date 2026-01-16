<?= $this->extend('layouts/employee_main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">My Asset Requests</h1>

    <button class="btn btn-primary my-2 font-bold" 
        data-bs-toggle="offcanvas"
        data-bs-target="#addRequestCanvas"
        title="Add Request">
        <i class="fa fa-plus mx-2"></i> Add Request
    </button>
</div>

<div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
    <h5 class="pb-2 mb-2">Filters</h5>
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <input type="text" name="asset_name" class="form-control" 
                   value="<?= esc($asset_name ?? '') ?>" 
                   placeholder="Search Asset">
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Select Status --</option>
                <option value="pending" <?= (isset($status) && $status=='pending') ? 'selected' : '' ?>>Pending</option>
                <option value="accepted" <?= (isset($status) && $status=='accepted') ? 'selected' : '' ?>>Accepted</option>
                <option value="denied" <?= (isset($status) && $status=='denied') ? 'selected' : '' ?>>Denied</option>
                <option value="onhold" <?= (isset($status) && $status=='onhold') ? 'selected' : '' ?>>On Hold</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="button" class="btn btn-primary w-100 font-bold" id="applyFilter">
                <i class="fa fa-search mx-2"></i>Search
            </button>
            <a href="<?= base_url('employee-request-asset') ?>" class="btn btn-secondary w-100 font-bold">Reset</a>
        </div>
    </div>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered" id="request-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Asset ID</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Asset</th>
                        <th>Asset ID</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </tfoot>

                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $row): ?>
                            <tr>
                                <td><?= esc($row['asset_name']) ?></td>
                                <td><?= esc($row['asset_code']) ?></td>
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

                                <td><?= esc(date('d-m-Y H:i:s', strtotime($row['request_date']))) ?></td>
                                <td><?= esc($row['remarks']) ?></td>
                                <td>
                                    <?php if($status === 'pending'): ?>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $row['id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">No actions</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No requests found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = $currentPage > 0 ? $currentPage : 1;
            $currentPage = max(1, min($currentPage, $totalPages));
            $lastPage = $totalPages;
            $currentCount = count($requests);

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
                               href="<?= $currentPage > 1 ? base_url('employee-request-asset?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('employee-request-asset?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('employee-request-asset?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('employee-request-asset?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('employee-request-asset?page=' . $lastPage) : '#' ?>">
                                Last
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addRequestCanvas" aria-labelledby="addRequestCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addRequestCanvasLabel">Create Asset Request</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body">
    <form id="requestForm">

      <div class="mb-3">
        <label class="form-label">Asset</label>
        <select id="asset_id" name="asset_id" class="form-select" required>
          <option value="">Select Asset</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Request Quantity</label>
        <input type="number" id="quantity" name="quantity" min="1" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Remarks</label>
        <textarea id="remarks" name="remarks" class="form-control"></textarea>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>

    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

let requestTable;

initDataTable();

function initDataTable() {
    if ($.fn.DataTable.isDataTable('#request-table')) {
        $('#request-table').DataTable().clear().destroy();
    }

    requestTable = $('#request-table').DataTable({
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
        asset_name: $("input[name=asset_name]").val() || urlParams.get('asset_name') || '',
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
                $link.attr('href', `<?= base_url('employee-request') ?>?page=${page}${queryString}`);
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
    window.location.href = `<?= base_url('employee-request-asset') ?>?${queryString}`;
});

// Populate form fields from URL on page load
function populateFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    $("input[name=asset_name]").val(urlParams.get('asset_name') || '');
    $("select[name=status]").val(urlParams.get('status') || '');
}

// Initialize filters from URL
populateFiltersFromURL();

// Update pagination links if filters are active
if (hasActiveFilters()) {
    updatePaginationLinks();
}

function loadAssets() {
    $.ajax({
        url: "<?= base_url('employee-request/assets') ?>",
        method: "GET",
        success: function (res) {
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

$('#addRequestCanvas').on('shown.bs.offcanvas', function () {
    loadAssets();
});

// SUBMIT REQUEST ------------------------
$('#requestForm').submit(function(e) {
    e.preventDefault();

    $.ajax({
        url: "<?= base_url('employee-request/create') ?>",
        type: "POST",
        data: {
            asset_id: $('#asset_id').val(),
            quantity: $('#quantity').val(),
            remarks: $('#remarks').val()
        },
        success: function(res) {
            if(res.success){
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.error || 'Error submitting request'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.error || 'Error submitting request'
            });
        }
    });
});

// DELETE REQUEST ------------------------
$('.deleteBtn').click(function() {
    let id = $(this).data('id');

    Swal.fire({
        title: "Are you sure?",
        text: "This request will be deleted permanently.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= base_url('/employee-request/delete') ?>/" + id,
                type: "DELETE",
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: res.message
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Error deleting request'
                    });
                }
            });
        }
    });
});

});
</script>

<?= $this->endSection() ?>