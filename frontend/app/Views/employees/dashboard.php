<?= $this->extend('layouts/employee_main') ?>

<?= $this->section('content') ?>

<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Dashboard</div>
                </div>
                <div class="ibox-body">
                    <p>Welcome, <?= esc(session('employee_name')) ?>!</p>

                    <?php if (!empty($requests)) : ?>
                        <h5>Your recent asset requests:</h5>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Asset Name</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $req) : ?>
                                    <tr>
                                        <td><?= esc($req['id']) ?></td>
                                        <td><?= esc($req['asset_name']) ?></td>
                                        <td><?= esc($req['status']) ?></td>
                                        <td><?= esc($req['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>No asset requests found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
