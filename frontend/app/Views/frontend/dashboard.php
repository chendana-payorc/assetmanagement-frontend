<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content fade-in-up">

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-primary color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $totalAssets ?></h2>
                    <div class="m-b-5">TOTAL ASSETS</div>
                    <i class="ti-archive widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $acceptedRequests ?></h2>
                    <div class="m-b-5">ACCEPTED REQUESTS</div>
                    <i class="ti-check-box widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-info color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $pendingRequests ?></h2>
                    <div class="m-b-5">PENDING REQUESTS</div>
                    <i class="ti-time widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-warning color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $onHoldRequests ?></h2>
                    <div class="m-b-5">ON HOLD REQUESTS</div>
                    <i class="ti-alert widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-danger color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $deniedRequests ?></h2>
                    <div class="m-b-5">DENIED REQUESTS</div>
                    <i class="ti-na widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-primary color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $totalAssign ?></h2>
                    <div class="m-b-5">TOTAL ASSIGNED ASSETS</div>
                    <i class="ti-share widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $returnAssets ?></h2>
                    <div class="m-b-5">RETURNED ASSETS</div>
                    <i class="ti-back-left widget-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-dark color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong"><?= $totalEmployees ?></h2>
                    <div class="m-b-5">TOTAL EMPLOYEES</div>
                    <i class="ti-user widget-stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-4">
        <!-- Requests by Status -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Requests by Status</div>
                </div>
                
                <div style="height:420px; width:520px; margin:auto;">
  <canvas id="requestsByStatus"></canvas>
</div>
               
            </div>
        </div>

        <!-- Asset Assignment Overview -->
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Asset Assignment Overview</div>
                </div>
                <div class="ibox-body">
                    <canvas id="assetAssignChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- All Assets Overview -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Assets Overview</div>
                </div>
                <div class="ibox-body">
                    <canvas id="allAssetsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('requestsByStatus'), {
        type: 'doughnut', 
        data: {
            labels: ['Accepted', 'Pending', 'On Hold', 'Denied'],
            datasets: [{
                label: 'Requests',
                data: [
                    <?= $acceptedRequests ?>,
                    <?= $pendingRequests ?>,
                    <?= $onHoldRequests ?>,
                    <?= $deniedRequests ?>
                ],
                backgroundColor: ['#28a745','#17a2b8','#ffc107','#dc3545']
            }]
        },
        options: {
            cutout: '60%',   // inner radius, smaller = thicker ring
        radius: '80%',   // outer radius
            plugins: {
                title: {
                    display: true,
                    text: 'Requests by Status'
                }
            }
        }
    });


    // All Assets Chart with details
    const assets = <?= json_encode($assets) ?>;
    new Chart(document.getElementById('allAssetsChart'), {
        type: 'bar',
        data: {
            labels: assets.map(a => a.name),
            datasets: [{
                label: 'Asset Count',
                data: assets.map(a => a.count),
                backgroundColor: '#007bff'
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const asset = assets[context.dataIndex];
                            return [
                                `Model: ${asset.model}`,
                                `Count: ${asset.count}`,
                                `Assigned: ${asset.assigned_assets}`,
                                `Remaining: ${asset.remaining_assets}`,
                                `Price: ${asset.price}`
                            ];
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'All Assets with Details'
                }
            }
        }
    });

    // Asset Assignment Overview Chart
    new Chart(document.getElementById('assetAssignChart'), {
        type: 'bar',
        data: {
            labels: ['Total Assignments', 'Returned Assets', 'Currently Assigned Quantity'],
            datasets: [{
                label: 'Assignments',
                data: [
                    <?= $totalAssign ?>,
                    <?= $returnAssets ?>,
                    <?= $currentAssignedQty ?>
                ],
                backgroundColor: ['#17a2b8','#6c757d','#28a745']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Asset Assignment Overview'
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>