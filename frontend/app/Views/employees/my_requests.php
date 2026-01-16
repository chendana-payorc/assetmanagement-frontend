<?= $this->extend('layouts/employee_main') ?>
<?= $this->section('content') ?>

<h3 class="fw-bold mb-3">My Requests</h3>

<div class="card shadow-sm p-3">

    <?php if (!empty($requests)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Asset</th>
                    <th>Status</th>
                    <th>Requested On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $r): ?>
                    <tr>
                        <td><?= esc($r['asset_name'] ?? '-') ?></td>
                        <td><?= esc($r['status'] ?? '-') ?></td>
                        <td><?= esc($r['created_at'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p class="text-muted">No asset requests found.</p>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
