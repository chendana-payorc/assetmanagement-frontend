<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Page Heading */
.page-heading {
    margin-bottom: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}

/* Card */
.card {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    padding: 30px;
    background-color: #fdfdfd;
}

/* Form labels */
.form-label {
    font-weight: 600;
    color: #495057;
}

/* Input fields */
.form-control {
    border-radius: 8px;
    padding: 10px 12px;
    border: 1px solid #ced4da;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
}

/* Update button */
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    font-weight: 600;
    border-radius: 8px;
    padding: 8px 25px;
    transition: background-color 0.2s, box-shadow 0.2s;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}

/* Card heading spacing */
.card h5 {
    margin-bottom: 20px;
}

/* Responsive adjustments */
@media (max-width: 767px) {
    .row.g-3 > .col-md-4 {
        margin-bottom: 15px;
    }
}
</style>

<div class="page-heading">
    <h1 class="page-title">Organization</h1>
</div>

<div class="page-content fade-in-up">

    <!-- ================= UPDATE SECTION ================= -->
    <div class="card mb-4">
        <form id="organizationUpdateForm">
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

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <!-- =============== END UPDATE SECTION ================== -->

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#organizationUpdateForm').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let id = form.find('input[name="id"]').val();

        $.post('<?= base_url('organization-update') ?>/' + id, form.serialize(), function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.message || 'Organization updated successfully',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: response.error || 'Update failed'
                });
            }
        }).fail(function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: xhr.responseJSON?.error || xhr.statusText
            });
        });
    });
});
</script>

<?= $this->endSection() ?>
