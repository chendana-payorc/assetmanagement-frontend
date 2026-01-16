<?= $this->extend('layouts/employee_main') ?>
<?= $this->section('content') ?>

<h3 class="fw-bold mb-3">Request Asset</h3>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card shadow-sm p-3">
    <form method="POST" action="<?= base_url('employee-submit-request') ?>">

        <div class="mb-3">
            <label class="form-label fw-semibold">Select Asset</label>
            <select name="asset_id" class="form-select" required>
                <option value="">-- Select Asset --</option>

                <?php foreach ($assets as $a): ?>
                    <option value="<?= $a['id'] ?>">
                        <?= esc($a['name']) ?> (Remaining: <?= esc($a['remaining_assets']) ?>)
                    </option>
                <?php endforeach; ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Note (optional)</label>
            <textarea name="note" class="form-control" rows="3" placeholder="Reason for request"></textarea>
        </div>

        <button class="btn btn-primary">Submit Request</button>
    </form>
</div>

<?= $this->endSection() ?>
