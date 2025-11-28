<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.card { border: 1px solid #dee2e6; border-radius: 12px; padding: 30px; background: #fff; }
.image-preview { width: 80px; height: 80px; border: 1px solid #ddd; border-radius: 10px; object-fit: contain; background: #f8f9fa; padding: 5px; }
</style>

<div class="page-heading">
    <h1 class="page-title">Organization</h1>
</div>

<div class="page-content fade-in-up">
    <div class="card mb-4">
        <form id="organizationUpdateForm" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= esc($organizations[0]['id'] ?? '') ?>">

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Organization Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc($organizations[0]['name'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($organizations[0]['email'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Contact No</label>
                    <input type="text" name="contact_no" class="form-control" value="<?= esc($organizations[0]['contact_no'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= esc($organizations[0]['address'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= esc($organizations[0]['country'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="<?= esc($organizations[0]['state'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= esc($organizations[0]['city'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Zipcode</label>
                    <input type="text" name="zipcode" class="form-control" value="<?= esc($organizations[0]['zipcode'] ?? '') ?>">
                </div>
  <div class="row g-3">
                <div class="col-md-4">
    <label class="form-label">Favicon</label>
    <input type="file" name="favicon" accept="image/*" class="form-control">
    <?php if (!empty($organizations[0]['favicon'])): ?>
    <img src="<?= env('Image_url').'uploads/organizations/'.$organizations[0]['favicon'] ?>" class="image-preview mt-2">
<?php endif; ?>

</div>

<div class="col-md-4">
    <label class="form-label">Logo</label>
    <input type="file" name="logo" accept="image/*" class="form-control">
    <?php if (!empty($organizations[0]['logo'])): ?>
    <img src="<?= env('Image_url').'uploads/organizations/'.$organizations[0]['logo'] ?>" class="image-preview mt-2">
<?php endif; ?>
</div>
</div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$('#organizationUpdateForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "<?= site_url('organization/store') ?>",
        type: "POST",
        data: formData,
        contentType: false,   // ✅ must be false
        processData: false,   // ✅ must be false
        dataType: 'json',
        // beforeSend: function() {
        //     Swal.fire({
        //         title: 'Please wait...',
        //         didOpen: () => Swal.showLoading(),
        //         allowOutsideClick: false
        //     });
        // },
        
        success: function(res) {
            Swal.close();
            if (res.success) {
            Swal.fire('Success', res.message, 'success').then(() => {
                location.reload();
            });
            }else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            Swal.fire('Error', 'Something went wrong', 'error');
        }
    });
});
</script>

<?= $this->endSection() ?>
