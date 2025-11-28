<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content fade-in-up">

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

</div>
<?= $this->endSection() ?>
