<?= $this->extend('layouts/employee_main') ?>
<?= $this->section('content') ?>

<h3 class="fw-bold mb-3">My Assigned Assets</h3>

<div class="card shadow-sm p-3">

    <?php if (!empty($assets)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Asset</th>
                    <th>Model</th>
                    <th>Assigned Quantity</th>
                    <th>Assigned Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $a): ?>
                    <tr>
                        <td><?= esc($a['asset_name'] ?? '-') ?></td>
                        <td><?= esc($a['model'] ?? '-') ?></td>
                        <td><?= esc($a['quantity'] ?? '-') ?></td>
                        <td><?= esc($a['assigned_at'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p class="text-muted">No assigned assets found.</p>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>
