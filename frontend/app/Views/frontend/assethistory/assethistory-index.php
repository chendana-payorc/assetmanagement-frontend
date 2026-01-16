<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Asset History</h1>
</div>

<div class="page-content fade-in-up">

    <!-- ================= FILTER SECTION ================= -->
    <div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
        <h5 class="pb-2 mb-2">Filters</h5>
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="asset_name" class="form-control" 
                       value="<?= esc($asset_name ?? '') ?>" 
                       placeholder="Search Asset Name">
            </div>

            <div class="col-md-3">
                <input type="text" name="model" class="form-control" 
                       value="<?= esc($model ?? '') ?>" 
                       placeholder="Search Model">
            </div>

            <div class="col-md-3">
                <input type="text" name="employee_name" class="form-control" 
                       value="<?= esc($employee_name ?? '') ?>" 
                       placeholder="Search Employee">
            </div>

            <div class="col-md-3">
                <input type="text" name="assigned_quantity" class="form-control" 
                       value="<?= esc($assigned_quantity ?? '') ?>" 
                       placeholder="Search Quantity">
            </div>

            <div class="col-md-3">
                <input type="date" name="assigned_date" class="form-control" 
                       value="<?= esc($assigned_date ?? '') ?>">
            </div>

            <div class="col-md-3">
                <input type="date" name="return_date" class="form-control" 
                       value="<?= esc($return_date ?? '') ?>">
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Select Status --</option>
                    <option value="assigned" <?= (isset($status) && $status=='assigned') ? 'selected' : '' ?>>Assigned</option>
                    <option value="returned" <?= (isset($status) && $status=='returned') ? 'selected' : '' ?>>Returned</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="button" id="applyFilter" class="btn btn-primary w-100 font-bold">
                    <i class="fa fa-search mx-2"></i>Search
                </button>
                <a href="<?= base_url('assethistory-list') ?>" class="btn btn-secondary font-bold w-100">Reset</a>
            </div>
        </div>
    </div>
    <!-- =============== END FILTER SECTION ================== -->

    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="history-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Model</th>
                        <th>Employee Name</th>
                        <th>Assigned Qty</th>
                        <th>Assigned Date</th>
                        <th>Returned Date</th>
                        <th>Handover Person</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>Asset Name</th>
                        <th>Model</th>
                        <th>Employee Name</th>
                        <th>Assigned Qty</th>
                        <th>Assigned Date</th>
                        <th>Returned Date</th>
                        <th>Handover Person</th>
                        <th>Status</th>
                    </tr>
                </tfoot>

                <tbody>
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $h): ?>
                        <tr>
                            <td><?= esc($h['asset_name']) ?></td>
                            <td><?= esc($h['model']) ?></td>
                            <td><?= esc($h['employee_name']) ?></td>
                            <td><?= esc($h['assigned_quantity']) ?></td>
                            <td><?= !empty($h['assigned_date'])
        ? date('d-m-Y H:i:s', strtotime($h['assigned_date']))
        : '-' ?></td>
                            <td><?= !empty($h['return_date'])
        ? date('d-m-Y H:i:s', strtotime($h['return_date']))
        : '-' ?></td>
                            <td><?= esc($h['handover_person'] ?? '-') ?></td>
                            <td>
                                <?php if (esc($h['status']) === 'assigned'): ?>
                                    <span class="badge bg-success">Assigned</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Returned</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No history found.</td></tr>
                    <?php endif; ?>
                </tbody>

            </table>

            <?php
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = $currentPage > 0 ? $currentPage : 1;
            $currentPage = max(1, min($currentPage, $totalPages));
            $lastPage = $totalPages;
            $currentCount = count($history);

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
                               href="<?= $currentPage > 1 ? base_url('assethistory-list?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('assethistory-list?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('assethistory-list?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('assethistory-list?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('assethistory-list?page=' . $lastPage) : '#' ?>">
                                Last
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

let historyTable;

initDataTable();

function initDataTable() {
    if ($.fn.DataTable.isDataTable('#history-table')) {
        $('#history-table').DataTable().clear().destroy();
    }

    historyTable = $('#history-table').DataTable({
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
        model: $("input[name=model]").val() || urlParams.get('model') || '',
        employee_name: $("input[name=employee_name]").val() || urlParams.get('employee_name') || '',
        assigned_quantity: $("input[name=assigned_quantity]").val() || urlParams.get('assigned_quantity') || '',
        assigned_date: $("input[name=assigned_date]").val() || urlParams.get('assigned_date') || '',
        return_date: $("input[name=return_date]").val() || urlParams.get('return_date') || '',
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
                $link.attr('href', `<?= base_url('assethistory-list') ?>?page=${page}${queryString}`);
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
    window.location.href = `<?= base_url('assethistory-list') ?>?${queryString}`;
});

// Populate form fields from URL on page load
function populateFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    $("input[name=asset_name]").val(urlParams.get('asset_name') || '');
    $("input[name=model]").val(urlParams.get('model') || '');
    $("input[name=employee_name]").val(urlParams.get('employee_name') || '');
    $("input[name=assigned_quantity]").val(urlParams.get('assigned_quantity') || '');
    $("input[name=assigned_date]").val(urlParams.get('assigned_date') || '');
    $("input[name=return_date]").val(urlParams.get('return_date') || '');
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