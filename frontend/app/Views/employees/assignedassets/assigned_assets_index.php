<?= $this->extend('layouts/employee_main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">My Assigned Assets</h1>
</div>

<div class="page-content fade-in-up">

    <!-- Filters -->
    <div class="row mx-2 mb-4 shadow-sm p-3 bg-light rounded">
        <h5 class="pb-2 mb-2">Filters</h5>
        <div class="row g-3 align-items-end">
            <!-- Asset Name -->
            <div class="col-md-3">
                <input type="text" name="asset" class="form-control"
                       value="<?= esc($asset ?? '') ?>"
                       placeholder="Search by Asset Name">
            </div>

            <!-- Model -->
            <div class="col-md-3">
                <input type="text" name="model" class="form-control"
                       value="<?= esc($model ?? '') ?>"
                       placeholder="Search by Model">
            </div>

            <!-- Quantity -->
            <div class="col-md-2">
                <input type="number" name="quantity" class="form-control"
                       value="<?= esc($quantity ?? '') ?>"
                       placeholder="Quantity">
            </div>

            <!-- Assigned Date -->
            <div class="col-md-2">
                <input type="date" name="assigned_date" class="form-control"
                       value="<?= esc($assigned_date ?? '') ?>">
            </div>

            <!-- Buttons -->
            <div class="col-md-2 d-flex gap-2">
                <button type="button" id="applyFilter" class="btn btn-primary w-100 font-bold">
                    <i class="fa fa-search mx-2"></i>Search
                </button>
                <a href="<?= base_url('employee/assigned-assets') ?>" class="btn btn-secondary w-100 font-bold">Reset</a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="assignedTable" width="100%">
                <thead>
                    <tr>
                        <th>Asset ID</th>
                        <th>Asset</th>
                        <th>Model</th>
                        <th>Quantity</th>
                        <th>Assigned Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Asset ID</th>
                        <th>Asset</th>
                        <th>Model</th>
                        <th>Quantity</th>
                        <th>Assigned Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </tfoot>

                <tbody>
                    <?php if (!empty($myAssignments)): ?>
                        <?php foreach ($myAssignments as $a): ?>
                            <tr>
                                <td><?= esc($a['asset_code'] ?? '-') ?></td>
                                <td><?= esc($a['asset_name'] ?? '-') ?></td>
                                <td><?= esc($a['model'] ?? '-') ?></td>
                                <td><?= esc($a['assigned_quantity'] ?? '-') ?></td>

                                <td>
                                    <?= isset($a['assigned_date']) ? date('d-m-Y H:i:s', strtotime($a['assigned_date'])) : '-' ?>
                                </td>

                                <td>
                                    <?= isset($a['return_date']) && $a['return_date'] != null 
                                        ? date('d-m-Y H:i:s', strtotime($a['return_date'])) 
                                        : '-' ?>
                                </td>

                                <td>
                                    <?php if (esc($a['status']) === 'assigned'): ?>
                                        <span class="badge bg-success">Assigned</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Returned</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No assigned assets found.</td></tr>
                    <?php endif; ?>
                </tbody>

            </table>

            <?php
            $limit = 5;
            $currentPage = (int) ($_GET['page'] ?? 1);
            $currentPage = $currentPage > 0 ? $currentPage : 1;
            $currentPage = max(1, min($currentPage, $totalPages));
            $lastPage = $totalPages;
            $currentCount = count($myAssignments);

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
                               href="<?= $currentPage > 1 ? base_url('employee/assigned-assets?page=1') : '#' ?>">
                                First
                            </a>
                        </li>

                        <!-- Prev -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage > 1 ? base_url('employee/assigned-assets?page=' . ($currentPage - 1)) : '#' ?>">
                                Prev
                            </a>
                        </li>

                        <!-- Page numbers -->
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link"
                                   href="<?= $i === $currentPage ? '#' : base_url('employee/assigned-assets?page=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('employee/assigned-assets?page=' . ($currentPage + 1)) : '#' ?>">
                                Next
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link"
                               href="<?= $currentPage < $lastPage ? base_url('employee/assigned-assets?page=' . $lastPage) : '#' ?>">
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

let assignedTable;

initDataTable();

function initDataTable() {
    if ($.fn.DataTable.isDataTable('#assignedTable')) {
        $('#assignedTable').DataTable().clear().destroy();
    }

    assignedTable = $('#assignedTable').DataTable({
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
        asset: $("input[name=asset]").val() || urlParams.get('asset') || '',
        model: $("input[name=model]").val() || urlParams.get('model') || '',
        quantity: $("input[name=quantity]").val() || urlParams.get('quantity') || '',
        assigned_date: $("input[name=assigned_date]").val() || urlParams.get('assigned_date') || ''
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
                $link.attr('href', `<?= base_url('employee/assigned-assets') ?>?page=${page}${queryString}`);
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
    window.location.href = `<?= base_url('employee/assigned-assets') ?>?${queryString}`;
});

// Populate form fields from URL on page load
function populateFiltersFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    
    $("input[name=asset]").val(urlParams.get('asset') || '');
    $("input[name=model]").val(urlParams.get('model') || '');
    $("input[name=quantity]").val(urlParams.get('quantity') || '');
    $("input[name=assigned_date]").val(urlParams.get('assigned_date') || '');
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