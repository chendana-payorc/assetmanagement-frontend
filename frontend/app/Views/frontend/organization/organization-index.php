<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Organization List</h1>
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addOrganizationCanvas" aria-controls="addOrganizationCanvas">
        Add Organization +
    </button>
</div>

<div class="page-content fade-in-up">

    <!-- ================= FILTER SECTION ================= -->
    <div class="card mb-4 shadow-sm p-3 bg-light rounded">
        <form method="GET" action="<?= base_url('organization-list') ?>">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= esc($name ?? '') ?>" placeholder="Search Name">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" value="<?= esc($email ?? '') ?>" placeholder="Search Email">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Contact No</label>
                    <input type="text" name="contact_no" class="form-control" value="<?= esc($contact_no ?? '') ?>" placeholder="Search Contact No">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= esc($address ?? '') ?>" placeholder="Search Address">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= esc($country ?? '') ?>" placeholder="Search Country">
                </div>

                <div class="col-md-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="<?= esc($state ?? '') ?>" placeholder="Search State">
                </div>

                <div class="col-md-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= esc($city ?? '') ?>" placeholder="Search City">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Zipcode</label>
                    <input type="text" name="zipcode" class="form-control" value="<?= esc($zipcode ?? '') ?>" placeholder="Search Zipcode">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    <a href="<?= base_url('organization-list') ?>" class="btn btn-outline-secondary w-100">Reset</a>
                </div>

            </div>
        </form>
    </div>
    <!-- =============== END FILTER SECTION ================== -->

    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="organization-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Organization Name</th>
                        <th>Email</th>
                        <th>Contact No</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Zipcode</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Organization Name</th>
                        <th>Email</th>
                        <th>Contact No</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Zipcode</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!empty($organizations)): ?>
                        <?php foreach ($organizations as $org): ?>
                            <tr>
                                <td><?= esc($org['name']) ?></td>
                                <td><?= esc($org['email']) ?></td>
                                <td><?= esc($org['contact_no']) ?></td>
                                <td><?= esc($org['address']) ?></td>
                                <td><?= esc($org['country']) ?></td>
                                <td><?= esc($org['state']) ?></td>
                                <td><?= esc($org['city']) ?></td>
                                <td><?= esc($org['zipcode']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary editBtn" data-id="<?= $org['id'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $org['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="text-center">No organizations found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= OFFCANVAS CREATE ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="addOrganizationCanvas" aria-labelledby="addOrganizationCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="addOrganizationCanvasLabel">Create Organization</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="organizationForm">
            <input type="hidden" name="id" id="org_id">
            <div class="form-group mb-3">
                <label class="required">Organization Name<span style="color:red">*</span></label>
                <input class="form-control" type="text" name="name" placeholder="Enter Name">
            </div>
            <div class="form-group mb-3">
                <label class="required">Email<span style="color:red">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email">
            </div>
            <div class="form-group mb-3">
                <label class="required">Contact No<span style="color:red">*</span></label>
                <input type="text" name="contact_no" class="form-control" placeholder="Enter Contact No">
            </div>
            <div class="form-group mb-3">
                <label class="required">Address<span style="color:red">*</span></label>
                <input type="text" name="address" class="form-control" placeholder="Enter Address">
            </div>
            <div class="form-group mb-3">
                <label class="required">Country<span style="color:red">*</span></label>
                <input type="text" name="country" class="form-control" placeholder="Enter Country">
            </div>
            <div class="form-group mb-3">
                <label class="required">State<span style="color:red">*</span></label>
                <input type="text" name="state" class="form-control" placeholder="Enter State">
            </div>
            <div class="form-group mb-3">
                <label class="required">City<span style="color:red">*</span></label>
                <input type="text" name="city" class="form-control" placeholder="Enter City">
            </div>
            <div class="form-group mb-3">
                <label class="required">Zipcode<span style="color:red">*</span></label>
                <input type="text" name="zipcode" class="form-control" placeholder="Enter Zipcode">
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= OFFCANVAS EDIT ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="editOrganizationCanvas" aria-labelledby="editOrganizationCanvasLabel">
    <div class="offcanvas-header">
        <h5 id="editOrganizationCanvasLabel">Edit Organization</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="organizationEditForm">
            <div id="editFormData"></div>
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-primary" type="submit">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#organization-table').DataTable({ pageLength: 10, ordering: false });

    // CREATE
    $('#organizationForm').submit(function(e) {
        e.preventDefault();
        $.post('<?= base_url('organization-store') ?>', $(this).serialize(), function(response){
            if(response.success){ location.reload(); }
            else{ alert(response.error || 'Error creating organization'); }
        }, 'json');
    });

    // DELETE
    $('.deleteBtn').click(function() {
        let id = $(this).data('id');
        if(confirm('Are you sure you want to delete this organization?')) {
            $.ajax({
                url: '<?= base_url('organization-delete') ?>/'+id,
                type: 'DELETE',
                success: function(){ location.reload(); },
                error: function(){ alert('Delete failed'); }
            });
        }
    });

    // EDIT
    $('.editBtn').click(function() {
        let id = $(this).data('id');
        $.post('<?= base_url('organization-edit') ?>', {id:id}, function(data){
            $('#editFormData').html(data);
            new bootstrap.Offcanvas(document.getElementById('editOrganizationCanvas')).show();
        });
    });

    // UPDATE
    $('#organizationEditForm').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let id = form.find('input[name="id"]').val();
        $.post('<?= base_url('organization-update') ?>/'+id, form.serialize(), function(response){
            if(response.success){ location.reload(); }
            else{ alert(response.error || 'Update failed'); }
        }, 'json');
    });
});
</script>

<?= $this->endSection() ?>
