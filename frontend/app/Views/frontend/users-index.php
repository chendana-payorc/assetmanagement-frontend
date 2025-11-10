<?= $this->extend('layouts/main') ?>
<style>
  .iti {
    width: 100%;
  }
  .iti input {
    padding-left: 52px !important; /* ensures flag area doesn't overlap text */
  }
</style>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Admin List</h1>
    <!-- Trigger Offcanvas -->
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addUserCanvas" aria-controls="addUserCanvas">
        Add User+
    </button>
</div>

<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body">
            <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Chandana</td>
                        <td>user1</td>
                        <td>chandana@gmail.com</td>
                        <td>9886887979</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-circle"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-sm btn-primary btn-circle"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger btn-circle"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="addUserCanvas" aria-labelledby="addUserCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addUserCanvasLabel">Create User</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="createUserForm">
       
            <div class="form-group">
                <label class="required">Name</label>
                <input class="form-control" type="text" name="name" placeholder="Enter Name">
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Designation</label>
                    <select class="form-select" name="designation_id" id="designationSelect" required>
                        <option value="">Select Designation</option>
                    </select>
                </div>
                <div class="col-sm-6 form-group">
                    <label>Department</label>
                    <select class="form-select" name="department_id" id="departmentSelect" required>
                        <option value="">Select Department</option>
                    </select>
                </div>
            </div>
           <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" placeholder="Enter Email">
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input id="phone" type="tel" name="phone_number" class="form-control" placeholder="Enter phone number">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password" placeholder="Enter Password">
            </div>
           

        <div class="form-group mt-3">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function() {

    $.ajax({
        url: '<?= base_url('/designations') ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.success && response.data) {
                $('#designationSelect').empty().append('<option value="">Select Designation</option>');
                $.each(response.data, function(index, item) {
                    $('#designationSelect').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            }
        },
        error: function() {
            console.error('Failed to load designations');
        }
    });

    // Fetch departments
    $.ajax({
        url: '<?= base_url('/departments') ?>',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.success && response.data) {
                $('#departmentSelect').empty().append('<option value="">Select Department</option>');
                $.each(response.data, function(index, item) {
                    $('#departmentSelect').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            }
        },
        error: function() {
            console.error('Failed to load departments');
        }
    });

    // Submit form
    $('#createUserForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('users-store') ?>', 
            method: 'POST',
            data: $(this).serialize(),
            success: function(response){
                alert('User added successfully!');
                $('#addUserCanvas').offcanvas('hide');
                location.reload();
            },
            error: function(){
                alert('Something went wrong!');
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
  const input = document.querySelector("#phone");
  window.intlTelInput(input, {
    initialCountry: "in",
    preferredCountries: ["in", "us", "gb", "au", "ae", "jp"],
    separateDialCode: true,
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
  });
});

});
</script>


<?= $this->endSection() ?>

